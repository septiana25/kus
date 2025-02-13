<?php
require_once '../../function/koneksi.php';
require_once '../class/barang.php';

$barang = new Barang($koneksi);

try {
	$item = $koneksi->real_escape_string($_GET["item"]);
	$result = $barang->fetchByItem($item);
	$data = [];
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_array()) {
			$data[] = $row['brg'];
		}
	} else {
		$data[] = 'Barang tidak ditemukan';
	}

	echo json_encode($data);
} catch (\Throwable $th) {
	echo json_encode(["error" => "An error occurred while fetching items."]);
} finally {
	$koneksi->close();
}
