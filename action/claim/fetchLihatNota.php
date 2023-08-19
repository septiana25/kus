<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';


	if ($_POST) {
	$idNota = $koneksi->real_escape_string($_POST['idNota']);
	// $idNota = 2;
	$sql = "SELECT toko, keputusan
		FROM tblNota
		JOIN tblDetNota USING(idNota)
		JOIN claim USING(id_claim)
		WHERE idNota=$idNota";
	$result = $koneksi->query($sql);

	if ($result->num_rows > 0) {
		$row = $result->fetch_array();
	}

	$koneksi->close();

	echo json_encode($row);

	}
?>