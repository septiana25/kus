<?php
	require_once '../../function/koneksi.php';	
	require_once '../../function/session.php';

	$id_brg = $_POST['id_brg'];
	// $id_brg = 1;

	$tblLimit = "SELECT id_brg, brg, btsLimit FROM tblLimit
	RIGHT JOIN barang using(id_brg) WHERE id_brg=$id_brg";
	$resLimit = $koneksi->query($tblLimit);

	if ($resLimit->num_rows > 0) {
		$row = $resLimit->fetch_array();
	}
	$koneksi->close();
	echo json_encode($row);
?>