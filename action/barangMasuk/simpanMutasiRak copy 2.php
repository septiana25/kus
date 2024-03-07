<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';
require_once '../class/saldo.php';
require_once '../class/barang.php';

$valid['success'] = array('success' => false, 'messages' => array());
$koneksi->begin_transaction();
$sql_success   = "";

$detailSaldoClass = new DetailSaldo($koneksi);
$saldoClass = new Saldo($koneksi);
$barangClass = new Barang($koneksi);

if ($_POST) {

	try {
		$inputs = getInputs($koneksi);
		extract($inputs);

		$hour    = date("H:i:s");
		$today   = date("Y-m-d H:i:s");
		$month   = SUBSTR($tgl, 5, -3);
		$year    = SUBSTR($tgl, 0, -6);

		$checkSaldoLastDate = $saldoClass->getSaldoByLastDate();
		$monthSaldoLastDate = SUBSTR($checkSaldoLastDate, 5, -3);
		$yearSaldoLastDate 	= SUBSTR($checkSaldoLastDate, 0, -6);

		if ($monthSaldoLastDate == $month && $yearSaldoLastDate == $year) {
			$noMutasi = $noMutasiAwal . $noMutasiAkhir;
			$handleMutasi = handleMutasi($saldoClass, $detailSaldoClass, $idDetailSaldo, $barangClass, $noMutasi, $tgl, $id_rakMTSRak, $qty, $keterangan, $month, $year);
			$valid = $handleMutasi;
		} else {
			$valid['success']  = false;
			$valid['messages'] = "Hanya Boleh Input Di Bulan Sekarang";
		}
	} catch (\Throwable $th) {
		//throw $th;
	} finally {
		var_dump($valid);
		die();
		echo json_encode($valid);
		$koneksi->close();
	}
}

function handleDetailItem($barangClass, $idDetailSaldo)
{
	$result = $barangClass->getItemJoinDetail($idDetailSaldo);
	return $result;
}
function handleDetailSaldo($detailSaldoClass, $idDetailSaldo)
{
	$result = $detailSaldoClass->getDetailSaldoByidDetailsaldo($idDetailSaldo);
	return $result;
}

function handleMutasi($saldoClass, $detailSaldoClass, $idDetailSaldo, $barangClass, $noMutasi, $tgl, $id_rakMTSRak, $qty, $keterangan, $month, $year)
{
	global $valid;

	$detailSaldo = handleDetailSaldo($detailSaldoClass, $idDetailSaldo);
	$row = $detailSaldo->fetch_assoc();
	$idDetailSaldo = $row['id_detailsaldo'];
	$id = $row['id'];
	$jumlah = $row['jumlah'];
	$tahunprod = $row['tahunprod'];

	$detailItem = handleDetailItem($barangClass, $id);
	$rowItem = $detailItem->fetch_assoc();
	$idRak = $rowItem['id_rak'];

	if ($jumlah < $qty) {
		$valid['success']  = false;
		$valid['messages'] = "Jumlah Barang Tidak Cukup";
		return $valid;
	}

	if ($id_rakMTSRak == $idRak) {
		$valid['success']  = false;
		$valid['messages'] = "Lokasi Pengirim Tidak Boleh Sama Dengan Lokasi Penerima";
		return $valid;
	}



	$checkSaldo = $saldoClass->getSaldoByid($id, $month, $year);

	if ($checkSaldo->num_rows === 0) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Tidak Ditemukan. Di Tabel Saldo";
		return $valid;
	}

	$resultCheckSaldo = $checkSaldo->fetch_array();
	$idSaldo = $resultCheckSaldo['id_saldo'];
	$saldoAkhir = $resultCheckSaldo['saldo_akhir'];

	$updateSaldo = handleUpdateSaldo($saldoClass, $detailSaldoClass, $idSaldo, $idDetailSaldo, $id, $tahunprod, $jumlah, $saldoAkhir);
	if (!$updateSaldo['success']) {
		return $updateSaldo;
	}


	$valid['success']  = true;
	$valid['messages'] = "Data Berhasil Disimpan";

	return $valid;
}

function handleUpdateSaldo($saldoClass, $detailSaldoClass, $idSaldo, $idDetailSaldo, $id, $tahunprod, $jumlah, $saldoAkhir)
{
	global $valid;

	try {
		$updateSaldo = $saldoClass->update($idSaldo, $saldoAkhir);
		$detailSaldo = handleSaveDetailSaldo($detailSaldoClass, $idDetailSaldo, $id, $tahunprod, $jumlah);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diupdate. Di Tabel Saldo. Error: ";
		return $valid;
	}

	if ($updateSaldo['success'] && $detailSaldo['success']) {
		return $updateSaldo;
	}

	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Data Gagal Diupdate. Di Tabel Saldo & Detail Saldo";
	return $valid;
}

function handleSaveDetailSaldo($detailSaldoClass, $idDetailSaldo, $id, $tahunprod, $jumlah)
{
	global $valid;

	$saveDetailSaldo = $detailSaldoClass->save($id, $tahunprod, $jumlah);
	if (!$saveDetailSaldo['success']) {
		$valid['success'] = false;
		return $valid;
	}

	$updateSaldoLama = $detailSaldoClass->update($idDetailSaldo, 0);
	if ($updateSaldoLama['affected_rows'] == 0) {
		$valid['success'] = false;
		return $valid;
	}

	$valid['success']  = true;
	$valid['messages'] = "Data Berhasil Disimpan";

	return $valid;
}

function getInputs($koneksi)
{
	$inputs = [
		"noMutasiAwal"	=> trim($koneksi->real_escape_string($_POST["NoMTSRak"])),
		"noMutasiAkhir"	=> trim($koneksi->real_escape_string($_POST["NoMTSRakAkhr"])),
		"tgl" 			=> trim($koneksi->real_escape_string($_POST["tglMTSRak"])),
		"id_rakMTSRak" 	=> trim($koneksi->real_escape_string($_POST["id_rakMTSRak"])),
		"idDetailSaldo" => trim($koneksi->real_escape_string($_POST["id_SaldoMutasi"])),
		"qty" 			=> trim($koneksi->real_escape_string($_POST["jmlMTSRak"])),
		"keterangan" 	=> trim(
			$koneksi->real_escape_string(
				isset($_POST["ketMTSRak"]) && !empty($_POST["ketMTSRak"]) ? $_POST["ketMTSRak"] : ""
			)
		)
	];

	return $inputs;
}
