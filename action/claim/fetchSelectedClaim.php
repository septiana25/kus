<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$id_claim = $koneksi->real_escape_string($_POST['id_claim']);

	if ($_POST) {

	$sql = "SELECT id_claim, pengaduan, toko, brg, keputusan, nominal
		FROM claim
		JOIN barang USING(id_brg) WHERE id_claim=$id_claim";
	$result = $koneksi->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_array();
	}

	$koneksi->close();

	echo json_encode($row);

	}
?>