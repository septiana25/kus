<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

require_once '../class/masuk.php';
require_once '../class/barang.php';
require_once '../class/saldo.php';
require_once '../class/detailsaldo.php';

$valid['success'] = array('success' => false, 'messages' => array());

$masukClass = new Masuk($koneksi);
$barangClass = new Barang($koneksi);
$saldoClass = new Saldo($koneksi);
$detailSaldoClass = new DetailSaldo($koneksi);

try {
	$koneksi->begin_transaction();
	$inputs 	= getInputs($koneksi);
	$namaLogin	= $_SESSION['nama'];
	extract($inputs);

	/* var_dump($idBrg . " ID Barang, " . $idRak . " ID Rak, " . $tgl . " TGL, " . $suratJLN . " Surat Jalan, " . $tahunprod . " Tahun Produksi, " . $jml . " Jumlah, " . $ket);
	die(); */
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
			$resultMasuk = handleMasuk($masukClass, $tgl, $suratJLN, $namaLogin);


			if (!$resultMasuk['success']) {
				throw new Exception($resultMasuk['messages']);
			}

			$idMsk = $resultMasuk['id'];
		} else {
			throw new Exception("Data Masuk Duplikat. Error-AIG-0A19 Id Masuk ");
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

		if (!$result['success']) {
			throw new Exception("Data Gagal Disimpan. Di Tabel Detail Masuk");
		}
		$valid['success']  = true;
		$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
		$koneksi->commit();
	} else {
		throw new Exception("Hanya Boleh Input Di Bulan Sekarang Error-AIG-0005");
	}
} catch (Exception $th) {
	$koneksi->rollback();
	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> " . $th->getMessage() ? $th->getMessage() : "Error";
} finally {
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
		$resultNewItem = handleNewItem($barangClass, $idBrg, $idRak);
		if (!$resultNewItem['success']) {
			throw new Exception($resultNewItem['messages']);
		}
		$id = $resultNewItem['id'];
	} else {
		throw new Exception("Data Detail Barang Duplikat. Di Tabel Saldo ");
	}
	$handleMasukDetail = handleMasukDetail($masukClass, $idMsk, $id, $jam, $jml, $ket, $tahunprod);

	if (!$handleMasukDetail['success']) {
		throw new Exception($handleMasukDetail['messages']);
	}

	$handleCheckSaldo = handleCheckSaldo($saldoClass, $detailSaldoClass, $id, $bulan, $tahun, $tahunprod, $jml, $tgl);
	if (!$handleCheckSaldo['success']) {
		throw new Exception($handleCheckSaldo['messages'] ? $handleCheckSaldo['messages'] : "Gagal Update Saldo");
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

	$valid['success'] = true;
	$valid['id'] = $insertDetailItem['id'];
	return $valid;
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

	$valid['success'] = true;
	$valid['id'] = $insertMasuk['id'];
	return $valid;
}

function handleMasukDetail($masukClass, $idMsk, $id, $jam, $jml, $ket, $tahunprod)
{
	global $valid;

	$insertMasukDetail = $masukClass->saveDetail($idMsk, $id, $jam, $jml, $ket);
	$insertTahunProd = $masukClass->saveTahunProd($insertMasukDetail['id'], $tahunprod);

	if ($insertMasukDetail['success'] && $insertTahunProd['success']) {
		$valid['success'] = true;
		$valid['id'] = $insertMasukDetail['id'];
		return $valid;
	}

	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail & Tahun Produksi Masuk ";
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
		$valid['success'] = true;
		$valid['id'] = $insertSaldo['id'];
		return $valid;
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
