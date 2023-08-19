<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';

if ($_POST)
{

	$id_kat = $koneksi->real_escape_string($_POST['id_kat']);

	$sql = "SELECT * FROM kat WHERE id_kat = $id_kat";
	$result = $koneksi->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_array();
	}

	$koneksi->close();

	echo json_encode($row);

}