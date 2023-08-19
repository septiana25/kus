<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/setjam.php';
	require_once '../../function/session.php';

	if ($_POST) {
		$nofak = $_POST['nofak'];
		$id_brg = $_POST['id_brg'];

		$query = "SELECT jml_klr FROM detail_keluar
			JOIN keluar USING(id_klr)
			JOIN detail_brg USING(id)
			JOIN barang USING(id_brg)
			WHERE no_faktur='$nofak' AND id_brg=$id_brg";

		$result = $koneksi->query($query);

		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
		}

	}
	
	echo json_encode($row);
?>