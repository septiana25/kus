<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$id_det_klr = $_POST['id_det_klr'];
	$sql = "SELECT id_det_klr, jml_klr, brg, rak, ket, tgl, toko, no_faktur
			FROM detail_keluar
			JOIN keluar USING(id_klr)
			JOIN detail_brg USING(id)
			JOIN barang USING(id_brg)
			JOIN rak USING(id_rak)
			JOIN toko USING(id_toko)
			WHERE  id_det_klr = $id_det_klr";
	$result = $koneksi->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_array();
	}

	$koneksi->close();

	echo json_encode($row);