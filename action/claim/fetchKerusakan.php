<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$query = "SELECT singkat_k, lengkap_k FROM kerusakan ORDER BY lengkap_k ASC";
	$result = $koneksi->query($query);
	$data = $result->fetch_all();
	$koneksi->close();
	echo json_encode($data);
?>