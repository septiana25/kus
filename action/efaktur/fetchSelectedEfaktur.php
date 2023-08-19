<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$id_klr = $_POST['id_klr'];
	if ($id_klr) {
		$sql = "SELECT id_klr, no_faktur, SUBSTRING(no_faktur, -11, 6) AS awal, SUBSTRING(no_faktur, -5) AS akhir FROM keluar WHERE id_klr = $id_klr";
		$result = $koneksi->query($sql);
		if ($result->num_rows > 0) {
			$row = $result->fetch_array();
		}
		$koneksi->close();
		echo json_encode($row);
	}
?>