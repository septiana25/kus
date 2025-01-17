<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$id_det_msk = $koneksi->real_escape_string($_POST['id_det_msk']);
	$sql = "SELECT id_det_msk, jml_msk, brg, tgl, rak.rak, suratJln, ket, id_msk, id
			FROM detail_masuk
			JOIN masuk  USING(id_msk)
			JOIN detail_brg USING(id)
			JOIN barang USING(id_brg)
			JOIN rak USING(id_rak)
			WHERE id_det_msk = $id_det_msk";
	$result = $koneksi->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_array();
	}

	$koneksi->close();

	echo json_encode($row);