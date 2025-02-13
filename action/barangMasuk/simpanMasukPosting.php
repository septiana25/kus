<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

require_once '../class/masuk.php';
require_once '../class/barang.php';
require_once '../class/saldo.php';
require_once '../class/pomasuk.php';

$valid['success'] = array('success' => false, 'messages' => array());
$koneksi->begin_transaction();
$sql_success   = "";

$masukClass = new Masuk($koneksi);
$barangClass = new Barang($koneksi);
$saldoClass = new Saldo($koneksi);
$poMasukClass = new PoMasuk($koneksi);

try {
	$inputs 	= getInputs($koneksi);
	$namaLogin	= $_SESSION['nama'];
	extract($inputs);

	$jam           = date("H:i:s");
	$tgl1          = date("Y-m-d H:i:s");
	$bulan         = SUBSTR($tgl, 5, -3);
	$tahun         = SUBSTR($tgl, 0, -6);

	$checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
	if ($checkSaldoLastDate == $bulan) {

		$checkNoPO = $masukClass->getNoPO($suratJLN);
		$checkNoPOByDate = $masukClass->getNoPOByDate($suratJLN, $tgl);
		$resultNoPO = $checkNoPO->fetch_array();

		if ($checkNoPO->num_rows == 1 && $checkNoPOByDate->num_rows == 1) {
			$idMsk = $resultNoPO['id_msk'];
			$result = handleCheckItem(
				$poMasukClass,
				$barangClass,
				$masukClass,
				$saldoClass,
				$idBrg,
				$idRak,
				$idMsk,
				$tgl,
				$jml,
				$jam,
				$ket,
				$bulan,
				$tahun,
				$idPoMskScanDetail,
				$idPoMsk
			);

			if ($result['success']) {
				$sql_success .= "success";
				header('location: ../../pomasukdetail.php?id=' . $idPoMsk . '&status=success');
			}
		} elseif ($checkNoPO->num_rows == 0 && $checkNoPOByDate->num_rows == 0) {
			$idMsk = handleMasuk($masukClass, $tgl, $suratJLN, $namaLogin);
			$result = handleCheckItem(
				$poMasukClass,
				$barangClass,
				$masukClass,
				$saldoClass,
				$idBrg,
				$idRak,
				$idMsk,
				$tgl,
				$jml,
				$jam,
				$ket,
				$bulan,
				$tahun,
				$idPoMskScanDetail,
				$idPoMsk
			);

			if ($result['success']) {
				$sql_success .= "success";
				header('location: ../../pomasukdetail.php?id=' . $idPoMsk . '&status=success');
			}
		} else {

			$valid['success']  = false;
			$valid['messages'] = "<strong>Warning! </strong> Data Masuk Duplikat. Error-AIG-0A19 Id Masuk ";
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

function getInputs($koneksi)
{
	$inputs = [
		"idBrg" => trim($koneksi->real_escape_string($_POST["id_brg"])),
		"idRak" 	=> trim($koneksi->real_escape_string($_POST["id_rak"])),
		"tgl" 		=> trim($koneksi->real_escape_string($_POST["tgl"])),
		"suratJLN" 	=> trim($koneksi->real_escape_string($_POST["suratJLN"])),
		"jml" 		=> trim($koneksi->real_escape_string($_POST["jml"])),
		"idPoMsk" 	=> trim($koneksi->real_escape_string($_POST["idPoMsk"])),
		"idPoMskScanDetail" 	=> trim($koneksi->real_escape_string($_POST["idPoMskScanDetail"])),
		"ket" 	=> trim(
			$koneksi->real_escape_string(
				isset($_POST["ket"]) && !empty($_POST["ket"]) ? $_POST["ket"] : ""
			)
		)
	];

	return $inputs;
}

function handleCheckItem(
	$poMasukClass,
	$barangClass,
	$masukClass,
	$saldoClass,
	$idBrg,
	$idRak,
	$idMsk,
	$tgl,
	$jml,
	$jam,
	$ket,
	$bulan,
	$tahun,
	$idPoMskScanDetail,
	$idPoMsk
) {
	global $valid;

	$checkItem = $barangClass->getItemById($idBrg, $idRak);
	$resultItem = $checkItem->fetch_array();
	if ($checkItem->num_rows == 1) {
		$id = $resultItem['id'];

		$detailMasuk = handleMasukDetail($masukClass, $idMsk, $id, $jam, $jml, $ket);
		$idDetMsk = $detailMasuk;

		$handlePoMasukDetail = handlePoMasukDetail($idMsk, $poMasukClass, $idPoMsk, $idDetMsk, $idPoMskScanDetail);
		if (!$handlePoMasukDetail['success']) {
			return $handlePoMasukDetail;
		}

		return handleCheckSaldo($saldoClass, $id, $bulan, $tahun, $jml, $tgl);
	} elseif ($checkItem->num_rows == 0) {
		$id = handleNewItem($barangClass, $idBrg, $idRak);

		$detailMasuk = handleMasukDetail($masukClass, $idMsk, $id, $jam, $jml, $ket);
		$idDetMsk = $detailMasuk;

		$handlePoMasukDetail = handlePoMasukDetail($idMsk, $poMasukClass, $idPoMsk, $idDetMsk, $idPoMskScanDetail);
		if (!$handlePoMasukDetail['success']) {
			return $handlePoMasukDetail;
		}

		return handleCheckSaldo($saldoClass, $id, $bulan, $tahun, $jml, $tgl);
	} else {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Detail Barang Duplikat. Di Tabel Saldo ";
		return $valid;
	}
}

function handlePoMasukDetail($idMsk, $poMasukClass, $idPoMsk, $idDetMsk, $idPoMskScanDetail)
{
	global $valid;
	$insertPoMasukDetail = $poMasukClass->saveDetailPO($idMsk, $idPoMsk, $idDetMsk, $idPoMskScanDetail);

	if (!$insertPoMasukDetail['success']) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail PO Masuk ";
		return $valid;
	}

	return $insertPoMasukDetail;
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

function handleMasukDetail($masukClass, $idMsk, $id, $jam, $jml, $ket)
{
	global $valid;

	$insertMasukDetail = $masukClass->saveDetail($idMsk, $id, $jam, $jml, $ket);

	if (!$insertMasukDetail['success']) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Masuk ";
		return $valid;
	}

	return $insertMasukDetail['id'];
}

function handleNewSaldo($saldoClass, $id, $tgl, $saldoAkhir)
{
	global $valid;

	$insertSaldo = $saldoClass->save($id, $tgl, $saldoAkhir);

	if (!$insertSaldo['success']) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo ";
		return $valid;
	}

	return $insertSaldo;
}

function handleUpdateSaldo($saldoClass, $id, $saldoAkhir)
{
	global $valid;

	try {
		$updateSaldo = $saldoClass->update($id, $saldoAkhir);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diupdate. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	if (!$updateSaldo['success'] || $updateSaldo['affected_rows'] == 0) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diupdate. Di Tabel Saldo ";
		return $valid;
	}

	return $updateSaldo;
}

function handleCheckSaldo($saldoClass, $id, $month, $year, $jml, $tgl)
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
		$total_saldo = $resultSaldo['saldo_akhir'] + $jml;
		return  handleUpdateSaldo($saldoClass, $resultSaldo['id_saldo'], $total_saldo);
	} elseif ($checkSaldo->num_rows == 0) {
		return handleNewSaldo($saldoClass, $id, $tgl, $jml);
	} else {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat. Di Tabel Saldo ";
	}
}
