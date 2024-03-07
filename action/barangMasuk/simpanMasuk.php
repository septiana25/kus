<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

require_once '../class/masuk.php';
require_once '../class/barang.php';
require_once '../class/saldo.php';
require_once '../class/detailsaldo.php';

$valid['success'] = array('success' => false, 'messages' => array());
$koneksi->begin_transaction();
$sql_success   = "";

$masukClass = new Masuk($koneksi);
$barangClass = new Barang($koneksi);
$saldoClass = new Saldo($koneksi);
$detailSaldoClass = new DetailSaldo($koneksi);

try {
	$inputs 	= getInputs($koneksi);
	$namaLogin	= $_SESSION['nama'];
	extract($inputs);

	$jam           = date("H:i:s");
	$tgl1          = date("Y-m-d H:i:s");
	$bulan         = SUBSTR($tgl, 5, -3);
	$tahun         = SUBSTR($tgl, 0, -6);

	$checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
	$monthSaldoLastDate = SUBSTR($checkSaldoLastDate, 5, -3);
	$yearSaldoLastDate = SUBSTR($checkSaldoLastDate, 0, -6);

	if ($monthSaldoLastDate == $bulan && $yearSaldoLastDate == $tahun) {

		$checkNoPO = $masukClass->getNoPO($suratJLN);
		$checkNoPOByDate = $masukClass->getNoPOByDate($suratJLN, $tgl);
		$resultNoPO = $checkNoPO->fetch_array();

		if ($checkNoPO->num_rows == 1 && $checkNoPOByDate->num_rows == 1) {
			$idMsk = $resultNoPO['id_msk'];
		} elseif ($checkNoPO->num_rows == 0 && $checkNoPOByDate->num_rows == 0) {
			$idMsk = handleMasuk($masukClass, $tgl, $suratJLN, $namaLogin);
		} else {

			$valid['success']  = false;
			$valid['messages'] = "<strong>Warning! </strong> Data Masuk Duplikat. Error-AIG-0A19 Id Masuk ";
			return $valid;
		}

		$result = handleCheckItem(
			$barangClass,
			$masukClass,
			$saldoClass,
			$detailSaldoClass,
			$idBrg,
			$idRak,
			$idMsk,
			$tgl,
			$tahunprod,
			$jml,
			$jam,
			$ket,
			$bulan,
			$tahun
		);

		if ($result['success']) {
			$valid['success']  = true;
			$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
			$sql_success .= "success";
		}
	} else {
		$valid['success']  = false;
		$valid['messages'] = "<strong>Warning! </strong> Hanya Boleh Input Di Bulan Sekarang Error-AIG-0005";
		return $valid;
	}
} catch (\Throwable $th) {
	error_log($th->getMessage());
	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Terjadi Kesalahan Hubungi Staf IT." . $th->getMessage();
} finally {
	if ($sql_success) {
		$koneksi->commit();
	} else {
		$koneksi->rollback();
	}
	$koneksi->close();
	echo json_encode($valid);
}

function getInputs($koneksi)
{
	$inputs = [
		"idBrg" 	=> trim($koneksi->real_escape_string($_POST["id_brg"])),
		"idRak" 	=> trim($koneksi->real_escape_string($_POST["id_rak"])),
		"tgl" 		=> trim($koneksi->real_escape_string($_POST["tgl"])),
		"suratJLN" 	=> trim($koneksi->real_escape_string($_POST["suratJLN"])),
		"tahunprod" => trim($koneksi->real_escape_string($_POST["tahunprod"])),
		"jml" 		=> trim($koneksi->real_escape_string($_POST["jml"])),
		"ket" 		=> trim(
			$koneksi->real_escape_string(
				isset($_POST["ket"]) && !empty($_POST["ket"]) ? $_POST["ket"] : ""
			)
		)
	];

	return $inputs;
}

function handleCheckItem(
	$barangClass,
	$masukClass,
	$saldoClass,
	$detailSaldoClass,
	$idBrg,
	$idRak,
	$idMsk,
	$tgl,
	$tahunprod,
	$jml,
	$jam,
	$ket,
	$bulan,
	$tahun
) {
	global $valid;

	$checkItem = $barangClass->getItemById($idBrg, $idRak);
	$resultItem = $checkItem->fetch_array();

	if ($checkItem->num_rows == 1) {
		$id = $resultItem['id'];
	} elseif ($checkItem->num_rows == 0) {
		$id = handleNewItem($barangClass, $idBrg, $idRak);
	} else {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Detail Barang Duplikat. Di Tabel Saldo ";
		return $valid;
	}
	$handleMasukDetail = handleMasukDetail($masukClass, $idMsk, $id, $jam, $jml, $ket, $tahunprod);
	$handleCheckSaldo = handleCheckSaldo($saldoClass, $detailSaldoClass, $id, $bulan, $tahun, $tahunprod, $jml, $tgl);

	if ($handleMasukDetail < 0) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Success! </strong>Data Gagal Disimpan. Di Tabel Detail Masuk";
		return $valid;
	}

	return $handleCheckSaldo;
}

function handleNewItem($barangClass, $id_barang, $idRak)
{
	global $valid;

	$insertDetailItem = $barangClass->saveDetail($id_barang, $idRak);

	if (!$insertDetailItem['success']) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Barang";
		return $valid;
	}

	return $insertDetailItem['id'];
}

function handleMasuk($masukClass, $tgl, $suratJLN, $namaLogin)
{
	global $valid;

	$insertMasuk = $masukClass->save($tgl, $suratJLN, $namaLogin);

	if (!$insertMasuk['success']) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Masuk ";
		return $valid;
	}

	return $insertMasuk['id'];
}

function handleMasukDetail($masukClass, $idMsk, $id, $jam, $jml, $ket, $tahunprod)
{
	global $valid;

	$insertMasukDetail = $masukClass->saveDetail($idMsk, $id, $jam, $jml, $ket);
	$insertTahunProd = $masukClass->saveTahunProd($insertMasukDetail['id'], $tahunprod);

	if ($insertMasukDetail['success'] && $insertTahunProd['success']) {
		return $insertMasukDetail['id'];
	}

	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Masuk ";
	return $valid;
}

function handleNewSaldo($saldoClass, $detailSaldoClass, $id, $tgl, $tahunprod, $jml)
{
	global $valid;

	try {
		$insertSaldo = $saldoClass->save($id, $tgl, $jml);
		$detailSaldo = handleDetailSaldo($detailSaldoClass, $id, $tahunprod, $jml);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	if ($insertSaldo['success'] && $detailSaldo['success']) {
		return $insertSaldo;
	}

	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo ";
	return $valid;
}

function handleDetailSaldo($detailSaldoClass, $id, $tahunprod, $jml)
{
	global $valid;

	try {
		$checkDetailSaldo = $detailSaldoClass->getDetailSaldoByidAndYearProd($id, $tahunprod);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diambil. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	if ($checkDetailSaldo->num_rows == 0) {
		$insertDetailSaldo = $detailSaldoClass->save($id, $tahunprod, $jml);

		if (!$insertDetailSaldo['success']) {
			$valid['success'] = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo ";
			return $valid;
		}

		return $insertDetailSaldo;
	} elseif ($checkDetailSaldo->num_rows == 1) {
		$resultDetailSaldo = $checkDetailSaldo->fetch_array();
		$idDetailSaldo = $resultDetailSaldo['id_detailsaldo'];
		$totalJumlah = $resultDetailSaldo['jumlah'] + $jml;
		$updateDetailSaldo = $detailSaldoClass->update($idDetailSaldo, $totalJumlah);

		if (!$updateDetailSaldo['success']) {
			$valid['success'] = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo ";
			return $valid;
		}

		return $updateDetailSaldo;
	} else {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Detail Saldo Duplikat. Di Tabel Saldo ";
		return $valid;
	}
}

function handleUpdateSaldo($saldoClass, $detailSaldoClass, $idSaldo, $id, $tahunprod, $jml, $saldoAkhir)
{
	global $valid;

	try {
		$updateSaldo = $saldoClass->update($idSaldo, $saldoAkhir);
		$detailSaldo = handleDetailSaldo($detailSaldoClass, $id, $tahunprod, $jml);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diupdate. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	if ($updateSaldo['success'] && $detailSaldo['success']) {
		return $updateSaldo;
	}

	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Data Gagal Diupdate. Di Tabel Saldo & Detail Saldo";
	return $valid;
}

function handleCheckSaldo($saldoClass, $detailSaldoClass, $id, $month, $year, $tahunprod, $jml, $tgl)
{
	global $valid;

	try {
		$checkSaldo = $saldoClass->getSaldoByid($id, $month, $year);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diambil. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	$resultSaldo = $checkSaldo->fetch_array();
	if ($checkSaldo->num_rows == 1) {
		$totalSaldo = $resultSaldo['saldo_akhir'] + $jml;
		return handleUpdateSaldo($saldoClass, $detailSaldoClass, $resultSaldo['id_saldo'], $id, $tahunprod, $jml, $totalSaldo);
	} elseif ($checkSaldo->num_rows == 0) {
		return handleNewSaldo($saldoClass, $detailSaldoClass, $id, $tgl, $tahunprod, $jml);
	} else {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat. Di Tabel Saldo ";
		return $valid;
	}
}
