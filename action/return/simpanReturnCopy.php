<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

require_once '../class/saldo.php';
require_once '../class/keluar.php';

$saldoClass = new Saldo($koneksi);
$classKeluar = new Keluar($koneksi);

$valid['success'] = array('success' => false, 'messages' => array());

try {
	$inputs = getInputs($koneksi);
	$inputs['noRetur'] = sprintf("R%s.%05d", date("y"), $inputs['fakturRetur']);
	$inputs['user'] = $_SESSION['nama'];

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

	handleSisaRetur($classKeluar, $inputs);

	$valid['success']  = true;
	$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
} catch (Exception $e) {
	$valid['success'] = false;
	$valid['messages'] = "<strong>Warning! </strong>" . $e->getMessage();
} finally {
	if (isset($koneksi)) {
		$koneksi->close();
	}
	header('Content-Type: application/json');
	echo json_encode($valid);
	exit;
}

function handleSisaRetur($classKeluar, $inputs)
{
	$result = $classKeluar->getDetailKeluar($inputs['id_det_klr']);
	$row = $result->fetch_assoc();
	$sisaRtr = $row['sisaRtr'];

	if ($sisaRtr < $inputs['jmlRtr']) {
		throw new Exception("Jumlah retur tidak boleh lebih besar dari sisa retur " . $sisaRtr . ". Error-AIG-0006");
	}
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
		"keterangan" => trim($koneksi->real_escape_string($_POST["keterangan"]))
	];

	return $inputs;
}
