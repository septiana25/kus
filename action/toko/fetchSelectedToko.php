<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';

if ($_POST)
{

	$id_toko = $koneksi->real_escape_string($_POST['id_toko']);

	$sql = "SELECT * FROM toko WHERE id_toko = $id_toko";
	$result = $koneksi->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_array();
	}

	$koneksi->close();

	echo json_encode($row);

}