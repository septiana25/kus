<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/pomasuk.php';

$pomasuk = new PoMasuk($koneksi);

try {
	$nopol = $koneksi->real_escape_string($_GET["nopol"]);
	$result = $pomasuk->fetchNopol($nopol);
	$data = [];
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_array()) {
			$data[] = $row['no_polisi'];
		}
	} else {
		$data[] = 'Barang tidak ditemukan';
	}

	echo json_encode($data);
} catch (\Throwable $th) {
	echo json_encode(["error" => "An error occurred while fetching Plat Nomor."]);
} finally {
	$koneksi->close();
}
