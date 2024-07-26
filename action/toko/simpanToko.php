<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/toko.php';

$tokoClass = new Toko($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
	$inputs = getInputs($koneksi);
	$result = handleSaveToko($tokoClass, $inputs);
	if (!$result['success']) {
		$valid['success'] = false;
		$valid['messages'] = $result['messages'];
	} else {
		$valid['success'] = true;
		$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
	}
} catch (Exception $e) {
	echo $e->getMessage();
} finally {
	$koneksi->close();
	echo json_encode($valid);
}

function handleSaveToko($tokoClass, $inputs)
{

	$nopol = $tokoClass->getTokoByKodetoko($inputs['kode_toko']);
	if ($nopol->num_rows > 0) {
		return [
			'success' => false,
			'messages' => "<strong>Error! </strong> Kode Toko Sudah Ada"
		];
	}

	$result = $tokoClass->insert($inputs);
	if ($result) {
		return [
			'success' => true
		];
	} else {
		return [
			'success' => false,
			'messages' => "<strong>Error! </strong> Gagal Disimpan"
		];
	}
}

function getInputs($koneksi)
{
	$inputs = [
		"kode_toko" => trim($koneksi->real_escape_string($_POST["kode_toko"])),
		"toko" => trim($koneksi->real_escape_string($_POST["toko"])),
		"alamat" => trim($koneksi->real_escape_string($_POST["alamat"]))
	];

	return $inputs;
}
