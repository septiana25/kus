<?php
require_once '../../function/koneksi.php';
require_once '../class/keluar.php';

$keluarClass = new Keluar($koneksi);

try {
	$pengirim = $koneksi->real_escape_string($_GET["pengirim"]);
	$result = $keluarClass->getSender($pengirim);
	$data = [];
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_array()) {
			$data[] = $row['pengirim'];
		}
	} else {
		$data[] = 'Pengirim tidak ditemukan';
	}

	echo json_encode($data);
} catch (\Throwable $th) {
	echo json_encode(["error" => "An error occurred while fetching items."]);
} finally {
	$koneksi->close();
}
