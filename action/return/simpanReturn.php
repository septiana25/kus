<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

require_once '../class/saldo.php';
require_once '../class/keluar.php';
require_once '../class/masuk.php';
require_once '../class/barang.php';
require_once '../class/saldo.php';
require_once '../class/detailsaldo.php';

$saldoClass = new Saldo($koneksi);
$keluarClass = new Keluar($koneksi);
$masukClass = new Masuk($koneksi);
$barangClass = new Barang($koneksi);
$saldoClass = new Saldo($koneksi);
$detailSaldoClass = new DetailSaldo($koneksi);

$valid['success'] = array('success' => false, 'messages' => array());

try {
	$koneksi->begin_transaction();
	$inputs = getInputs($koneksi);
	$inputs = handleAssigmentData($keluarClass, $inputs);

	$checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
	if (!$checkSaldoLastDate) {
		throw new Exception("Gagal mendapatkan tanggal saldo terakhir.");
	}

	$monthSaldoLastDate = SUBSTR($checkSaldoLastDate, 5, -3);
	$yearSaldoLastDate = SUBSTR($checkSaldoLastDate, 0, -6);

	$lastSaldoDate = new DateTime($checkSaldoLastDate);
	$returDate = new DateTime($inputs['tglRtr']);

	if ($lastSaldoDate->format('Ym') !== $returDate->format('Ym')) {
		throw new Exception("Hanya boleh input di bulan yang sama dengan saldo terakhir. Error-AIG-0005");
	}

	$inputs = handleSisaRetur($keluarClass, $barangClass, $inputs);

	$inputs = handleExistBarang($barangClass, $inputs);

	$inputs = handleMasuk($masukClass, $inputs);

	handleDetailMasuk($masukClass, $inputs);

	handleUpdateSaldo($saldoClass, $detailSaldoClass, $inputs, $monthSaldoLastDate, $yearSaldoLastDate);

	$valid['success']  = true;
	$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan ";
	$koneksi->commit();
} catch (Exception $e) {
	$valid['success'] = false;
	$valid['messages'] = "<strong>Warning! </strong>" . $e->getMessage();
	$koneksi->rollback();
} finally {
	if (isset($koneksi)) {
		$koneksi->close();
	}
	header('Content-Type: application/json');
	echo json_encode($valid);
	exit;
}

function handleUpdateSaldo($saldoClass, $detailSaldoClass, $inputs, $monthSaldoLastDate, $yearSaldoLastDate)
{
	$resulSaldoByid = $saldoClass->getSaldoByid($inputs['id'], $monthSaldoLastDate, $yearSaldoLastDate);
	if (!$resulSaldoByid) {
		throw new Exception("Gagal mendapatkan saldo.");
	}

	if ($resulSaldoByid->num_rows == 0) {
		$insertSaldo = $saldoClass->save($inputs['id'], $inputs['tglRtr'], $inputs['jmlRtr']);
		if (!$insertSaldo['success']) {
			throw new Exception("Gagal menyimpan saldo. Error-AIG-0016");
		}

		if (!handleUpdateDetailSaldo($detailSaldoClass, $inputs)) {
			throw new Exception("Gagal menyimpan detail saldo. Error-AIG-0019");
		}

		return;
	}

	$row = $resulSaldoByid->fetch_assoc();
	$updateSaldo = $saldoClass->updateSaldoPlus($row['id_saldo'], $inputs['jmlRtr']);
	if (!$updateSaldo['success']) {
		throw new Exception("Gagal mengupdate saldo. Error-AIG-0020");
	}
	if (!handleUpdateDetailSaldo($detailSaldoClass, $inputs)) {
		throw new Exception("Gagal menyimpan detail saldo. Error-AIG-0019");
	}
}

function handleUpdateDetailSaldo($detailSaldoClass, $inputs)
{
	$result = $detailSaldoClass->getDetailSaldoByidAndYearProd($inputs['id'], $inputs['tahunprod']);
	if (!$result) {
		// throw new Exception("Gagal mendapatkan detail saldo. Error-AIG-0017");
		return false;
	}

	if ($result->num_rows > 1) {
		// throw new Exception("Duplikat detail saldo. Error-AIG-0017");
		return false;
	}

	if ($result->num_rows == 0) {
		$insertDetailSaldo = $detailSaldoClass->save($inputs['id'], $inputs['tahunprod'], $inputs['jmlRtr']);
		if (!$insertDetailSaldo['success']) {
			return false;
		}

		return true;
	}
	$row = $result->fetch_assoc();

	$updateDetailSaldo = $detailSaldoClass->updatePlus($row['id_detailsaldo'], $inputs['jmlRtr']);
	if (!$updateDetailSaldo['success']) {
		return false;
	}

	return true;
}

function handleDetailMasuk($masukClass, $inputs)
{
	$jam         = date("H:i:s");
	$status_msk  = '0';
	$rak         = NULL;
	$insertDetailMasuk = $masukClass->saveDetail($inputs['id_msk'], $inputs['id'], $jam, $inputs['jmlRtr'], $inputs['keterangan'], $status_msk, $rak, $inputs['id_det_klr']);
	if (!$insertDetailMasuk['success']) {
		throw new Exception("Gagal menyimpan detail masuk. Error-AIG-0009");
	}

	$id_det_msk = $insertDetailMasuk['id'];
	$insertTahunProd = $masukClass->saveTahunProd($id_det_msk, $inputs['tahunprod']);
	if (!$insertTahunProd['success']) {
		throw new Exception("Gagal menyimpan tahun produksi. Error-AIG-0010");
	}
}

function handleMasuk($masukClass, $inputs)
{
	$checkNoPO = $masukClass->getNoPO($inputs['noRetur']);
	$checkNoPOByDate = $masukClass->getNoPOByDate($inputs['noRetur'], $inputs['tglRtr']);

	$noPOCount = $checkNoPO->num_rows;
	$noPOByDateCount = $checkNoPOByDate->num_rows;

	if ($noPOCount > 1) {
		throw new Exception("Nomor duplikat. Error-AIG-0007");
	}

	if ($noPOCount == 1) {
		if ($noPOByDateCount == 1) {
			$row = $checkNoPOByDate->fetch_assoc();
			if (!$row) {
				throw new Exception("Gagal mengambil data retur. Error-AIG-0008");
			}

			$inputs['id_msk'] = $row['id_msk'];
			return $inputs;
		}

		throw new Exception("Nomor retur sudah ada. Error-AIG-0007");
	}

	$tgl = $inputs['tglRtr'];
	$noPO = $inputs['noRetur'];
	$nama = $inputs['user'];
	$retur = 1;
	$noFaktur = $inputs['noFaktur'];
	$insertMasuk = $masukClass->save($tgl, $noPO, $nama, $retur, $noFaktur);

	if (!$insertMasuk['success']) {
		throw new Exception("Gagal menyimpan data masuk. Error-AIG-0008");
	}

	$inputs['id_msk'] = $insertMasuk['id'];

	return $inputs;
}

function handleExistBarang($barangClass, $inputs)
{
	$result = $barangClass->getItemById($inputs['id_brg'], $inputs['id_rakRtr']);
	if (!$result) {
		throw new Exception("Gagal mengambil data barang. Error-AIG-0014");
	}

	if ($result->num_rows == 0) {
		$insertDetailBarang = $barangClass->saveDetail($inputs['id_brg'], $inputs['id_rakRtr']);
		if (!$insertDetailBarang['success']) {
			throw new Exception("Gagal menyimpan detail barang. Error-AIG-0015");
		}

		$inputs['id'] = $insertDetailBarang['id'];
		return $inputs;
	}

	$row = $result->fetch_assoc();
	$inputs['id'] = $row['id'];

	return $inputs;
}

function handleSisaRetur($keluarClass, $barangClass, $inputs)
{
	$result = $keluarClass->getDetailKeluar($inputs['id_det_klr']);
	$row = $result->fetch_assoc();
	$sisaRtr = $row['sisaRtr'];

	if ($sisaRtr < $inputs['jmlRtr']) {
		throw new Exception("Jumlah retur tidak boleh lebih besar dari sisa retur " . $sisaRtr . ". Error-AIG-0006");
	}

	$updateSisaRtr = $keluarClass->updateSisaRtr($inputs['id_det_klr'], $inputs['jmlRtr']);
	if (!$updateSisaRtr['success']) {
		throw new Exception("Gagal mengupdate sisa retur. Error-AIG-0013");
	}

	$resulBarang = $barangClass->getItemJoinDetail($row['id']);
	if (!$resulBarang || $resulBarang->num_rows == 0) {
		throw new Exception("Gagal mengambil data barang. Error-AIG-0014");
	}

	$rowBarang = $resulBarang->fetch_assoc();

	$inputs['id_brg'] = $rowBarang['id_brg'];

	return $inputs;
}

function handleAssigmentData($keluarClass, $inputs)
{
	$result = $keluarClass->getByIdKlr($inputs['id_klr']);
	if (!$result || $result->num_rows == 0) {
		throw new Exception("Gagal mengambil data keluar. Error-AIG-0011");
	}
	$row = $result->fetch_assoc();

	$inputs['noFaktur'] = $row['no_faktur'];
	$inputs['noRetur'] = sprintf("R%s.%08d", date("y"), $inputs['fakturRetur']);
	$inputs['user'] = $_SESSION['nama'];

	return $inputs;
}

function getInputs($koneksi)
{
	$inputs = [
		"id_klr" => trim($koneksi->real_escape_string($_POST["NofakAwal"])),
		"fakturRetur" => trim($koneksi->real_escape_string($_POST["fakturRetur"])),
		"tglRtr" => trim($koneksi->real_escape_string($_POST["tglRtr"])),
		"id_det_klr" => trim($koneksi->real_escape_string($_POST["id_det_klr"])),
		"id_rakRtr" => trim($koneksi->real_escape_string($_POST["id_rakRtr"])),
		"jmlRtr" => trim($koneksi->real_escape_string($_POST["jmlRtr"])),
		"tahunprod" => trim($koneksi->real_escape_string($_POST["tahunprod"])),
		"keterangan" => trim($koneksi->real_escape_string($_POST["keterangan"]))
	];
	return $inputs;
}

/* 
Testing:
1. check Tanggal Saldo = Tanggal Retur (OK)
2. check Sisa Retur (OK)
3. check Gagal Insert Masuk (OK)
4. check Nomor Retur sudah ada (OK)
5. check Id Barang (OK)
6. check Id Masuk (OK)
7. check Id Detail Masuk (OK)
8. check Insert Saldo (OK)
9. check Update Saldo (OK)
10. check Update Detail Saldo (OK)
*/