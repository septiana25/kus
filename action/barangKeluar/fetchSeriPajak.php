<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';


	$sql = "SELECT seriPajak FROM tblSeriPajak LIMIT 0,1";
	$result = $koneksi->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_array();
	}

	$koneksi->close();

	echo json_encode($row);