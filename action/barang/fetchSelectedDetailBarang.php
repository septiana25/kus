<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

if ($_POST) {

	$id = $_POST['id'];
	$sql = "SELECT * FROM detail_brg WHERE id = $id";
	$result = $koneksi->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_array();
	}


	$koneksi->close();

	echo json_encode($row);
}