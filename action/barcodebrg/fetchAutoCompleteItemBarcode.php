<?php
require_once '../../function/koneksi.php';
require_once '../class/barcodebarang.php';

$barcodeBarang = new BarocdeBarang($koneksi);

try {
	$item = $koneksi->real_escape_string($_GET["item"]);
	$result = $barcodeBarang->fetchByItem($item);
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
