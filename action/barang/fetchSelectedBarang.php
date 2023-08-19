<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

if ($_POST) {
	$id_brg = $koneksi->real_escape_string($_POST['id_brg']);
	$sql = "SELECT * FROM barang WHERE id_brg = $id_brg";
	$result = $koneksi->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_array();
	}

	$koneksi->close();

	echo json_encode($row);
}