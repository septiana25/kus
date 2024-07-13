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

		$noMutasi = $noMutasiAwal . $noMutasiAkhir;
		$checkNoPO = $masukClass->getNoPO($noMutasi);
		$checkNoPOByDate = $masukClass->getNoPOByDate($noMutasi, $tgl);
		$resultNoPO = $checkNoPO->fetch_array();

		$detailSaldo = $detailSaldoClass->getDetailSaldoByidDetailsaldo($idDetailSaldo);
		$resultDetailSaldo = $detailSaldo->fetch_array();
		$tahunprod = $resultDetailSaldo['tahunprod'];
		$idOld = $resultDetailSaldo['id'];
		$jumlahDetaiLSaldo = $resultDetailSaldo['jumlah'];

		$detailItem = $barangClass->getItemJoinDetail($idOld);
		$rowItem = $detailItem->fetch_assoc();
		$idRak = $rowItem['id_rak'];
		$rakAsal = $rowItem['rak'];

		if ($id_rakMTSRak == $idRak) {
			$valid['success']  = false;
			$valid['messages'] = "Lokasi Pengirim Tidak Boleh Sama Dengan Lokasi Penerima";
			return $valid;
		}

		if ($checkNoPO->num_rows == 1 && $checkNoPOByDate->num_rows == 1) {
			$idMsk = $resultNoPO['id_msk'];
		} elseif ($checkNoPO->num_rows == 0 && $checkNoPOByDate->num_rows == 0) {
			$idMsk = handleMasuk($masukClass, $tgl, $noMutasi, $namaLogin);
		} else {
			$valid['success']  = false;
			$valid['messages'] = "<strong>Warning! </strong> No Mutasi Sudah Ada.";
			return $valid;
		}

		$result = handleCheckItem(
			$barangClass,
			$masukClass,
			$saldoClass,
			$detailSaldoClass,
			$id_brgMutasi,
			$id_rakMTSRak,
			$idMsk,
			$tgl,
			$tahunprod,
			$jml,
			$jam,
			$ket,
			$bulan,
			$tahun,
			$idOld,
			$jumlahDetaiLSaldo,
			$idDetailSaldo,
			$rakAsal
		);
		if ($result['success']) {
			$valid['success']  = true;
			$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
			$sql_success .= "success";
		}
	} else {

		$valid['success']  = false;
		$valid['messages'] = "<strong>Warning! </strong> Hanya Boleh Input Di Bulan Sekarang Error-AIG-0005";
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

function handleCheckItem(
	$barangClass,
	$masukClass,
	$saldoClass,
	$detailSaldoClass,
	$id_brgMutasi,
	$id_rakMTSRak,
	$idMsk,
	$tgl,
	$tahunprod,
	$jml,
	$jam,
	$ket,
	$bulan,
	$tahun,
	$idOld,
	$jumlahDetaiLSaldo,
	$idDetailSaldoOld,
	$rakAsal
) {
	global $valid;

	$checkItem = $barangClass->getItemById($id_brgMutasi, $id_rakMTSRak);
	$resultItem = $checkItem->fetch_array();

	if ($checkItem->num_rows == 1) {
		$id = $resultItem['id'];
	} elseif ($checkItem->num_rows == 0) {
		$id = handleNewItem($barangClass, $id_brgMutasi, $id_rakMTSRak);

		if ($id === NULL || $id < 0) {
			$valid['success'] = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Barang";
			return $valid;
		}
	} else {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Detail Barang Duplikat. Di Tabel Saldo ";
		return $valid;
	}

	$handleMasukDetail = handleMasukDetail($masukClass, $idMsk, $id, $jam, $jml, $ket, $tahunprod, $rakAsal);
	$handleCheckSaldo =  handleCheckSaldo($saldoClass, $detailSaldoClass, $id, $bulan, $tahun, $tahunprod, $jml, $tgl, $idOld, $jumlahDetaiLSaldo, $idDetailSaldoOld);

	if ($handleMasukDetail === NULL || $handleMasukDetail < 0) {
		return $handleMasukDetail;
	}
	return $handleCheckSaldo;

	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Barang";
	return $valid;
}

function handleNewItem($barangClass, $id_barang, $id_rakMTSRak)
{
	global $valid;

	$insertDetailItem = $barangClass->saveDetail($id_barang, $id_rakMTSRak);

	if (!$insertDetailItem['success']) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Barang";
		return $valid;
	}

	return $insertDetailItem['id'];
}

function handleMasuk($masukClass, $tgl, $noMutasi, $namaLogin)
{
	global $valid;

	$insertMasuk = $masukClass->save($tgl, $noMutasi, $namaLogin, 3);

	if (!$insertMasuk['success']) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Masuk ";
		return $valid;
	}

	return $insertMasuk['id'];
}

function handleMasukDetail($masukClass, $idMsk, $id, $jam, $jml, $ket, $tahunprod, $rakAsal)
{
	global $valid;

	$insertMasukDetail = $masukClass->saveDetail($idMsk, $id, $jam, $jml, $ket, '0', $rakAsal);
	$insertTahunProd = $masukClass->saveTahunProd($insertMasukDetail['id'], $tahunprod);

	if ($insertMasukDetail['success'] && $insertTahunProd['success']) {
		return $insertMasukDetail['id'];
	}

	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Masuk ";
	return $valid;
}

function handleDetailSaldo($detailSaldoClass, $id, $tahunprod, $jml, $jumlahDetaiLSaldo, $idDetailSaldoOld)
{
	global $valid;

	try {
		$checkDetailSaldo = $detailSaldoClass->getDetailSaldoByidAndYearProd($id, $tahunprod);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diambil. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	$updateDetailSaldoOld = $detailSaldoClass->update($idDetailSaldoOld, $jumlahDetaiLSaldo - $jml);

	if (!$updateDetailSaldoOld['success']) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan";
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

		if ($updateDetailSaldo['affected_rows'] == 0) {
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

function handleNewSaldo($saldoClass, $detailSaldoClass, $id, $tgl, $tahunprod, $jml, $idSaldoOld, $totalSaldoOld, $jumlahDetaiLSaldo, $idDetailSaldoOld)
{
	global $valid;

	try {
		$insertSaldo = $saldoClass->save($id, $tgl, $jml);
		$updateSaldoOld = $saldoClass->update($idSaldoOld, $totalSaldoOld);
		$detailSaldo = handleDetailSaldo($detailSaldoClass, $id, $tahunprod, $jml, $jumlahDetaiLSaldo, $idDetailSaldoOld);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	if ($insertSaldo['success'] && $detailSaldo['success'] && $updateSaldoOld['affected_rows']) {
		return $insertSaldo;
	}

	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo ";
	return $valid;
}

function handleUpdateSaldo($saldoClass, $detailSaldoClass, $idSaldo, $id, $tahunprod, $jml, $saldoAkhir, $idSaldoOld, $totalSaldoOld, $jumlahDetaiLSaldo, $idDetailSaldoOld)
{
	global $valid;

	try {
		$updateSaldo = $saldoClass->update($idSaldo, $saldoAkhir);
		$updateSaldoOld = $saldoClass->update($idSaldoOld, $totalSaldoOld);

		$detailSaldo = handleDetailSaldo($detailSaldoClass, $id, $tahunprod, $jml, $jumlahDetaiLSaldo, $idDetailSaldoOld);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diupdate. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	if ($updateSaldo['affected_rows'] == 1 && $updateSaldoOld['affected_rows'] == 1 && $detailSaldo['success']) {
		$valid['success'] = true;
		$valid['messages'] = "<strong>Success! </strong> Data Berhasil Diupdate.";
		return $valid;
	}

	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Data Gagal Diupdate. Di Tabel Saldo & Detail Saldo";
	return $valid;
}

function handleCheckSaldo($saldoClass, $detailSaldoClass, $id, $month, $year, $tahunprod, $jml, $tgl, $idOld, $jumlahDetaiLSaldo, $idDetailSaldoOld)
{
	global $valid;

	try {
		$checkSaldoNew = $saldoClass->getSaldoByid($id, $month, $year);
		$checkSaldoOld = $saldoClass->getSaldoByid($idOld, $month, $year);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diambil. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	$resultSaldoNew = $checkSaldoNew->fetch_array();
	$resultSaldoOld = $checkSaldoOld->fetch_array();

	if ($checkSaldoOld->num_rows == 0) {
		$valid['success']  = false;
		$valid['messages'] = "Data Saldo Lama Tidak Ditemukan. Di Tabel Saldo";
		return $valid;
	}

	if ($resultSaldoOld['saldo_akhir'] < $jml || $jumlahDetaiLSaldo < $jml) {
		$valid['success']  = false;
		$valid['messages'] = "Saldo Barang Tidak Cukup";
		return $valid;
	}

	$idSaldoOld = $resultSaldoOld['id_saldo'];
	$totalSaldoOld = $resultSaldoOld['saldo_akhir'] - $jml;

	if ($checkSaldoNew->num_rows == 1) {
		$totalSaldoNew = $resultSaldoNew['saldo_akhir'] + $jml;
		return handleUpdateSaldo($saldoClass, $detailSaldoClass, $resultSaldoNew['id_saldo'], $id, $tahunprod, $jml, $totalSaldoNew, $idSaldoOld, $totalSaldoOld, $jumlahDetaiLSaldo, $idDetailSaldoOld);
	} elseif ($checkSaldoNew->num_rows == 0) {
		return handleNewSaldo($saldoClass, $detailSaldoClass, $id, $tgl, $tahunprod, $jml, $idSaldoOld, $totalSaldoOld, $jumlahDetaiLSaldo, $idDetailSaldoOld);
	} else {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat. Di Tabel Saldo ";
		return $valid;
	}
}

function getInputs($koneksi)
{
	$inputs = [
		"noMutasiAwal"	=> trim($koneksi->real_escape_string($_POST["NoMTSRak"])),
		"noMutasiAkhir"	=> trim($koneksi->real_escape_string($_POST["NoMTSRakAkhr"])),
		"tgl" 			=> trim($koneksi->real_escape_string($_POST["tglMTSRak"])),
		"id_brgMutasi" 	=> trim($koneksi->real_escape_string($_POST["id_brgMutasi"])),
		"id_rakMTSRak" 	=> trim($koneksi->real_escape_string($_POST["id_rakMTSRak"])),
		"idDetailSaldo" => trim($koneksi->real_escape_string($_POST["id_SaldoMutasi"])),
		"jml" 			=> trim($koneksi->real_escape_string($_POST["jmlMTSRak"])),
		"ket" 	=> trim(
			$koneksi->real_escape_string(
				isset($_POST["ketMTSRak"]) && !empty($_POST["ketMTSRak"]) ? $_POST["ketMTSRak"] : ""
			)
		)
	];

	return $inputs;
}
