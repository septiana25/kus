<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';

if ($_POST)
{
	$id_rak = $koneksi->real_escape_string($_POST['id_rak']);
	
	$sql = "SELECT * FROM rak WHERE id_rak = $id_rak";
	$result = $koneksi->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_array();
	}

	$koneksi->close();

	echo json_encode($row);
}
