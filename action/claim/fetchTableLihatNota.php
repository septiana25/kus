<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/setjam.php';
	require_once '../../function/session.php';

	$idNota = $_POST['idNota'];

	$query = "SELECT pengaduan, brg, pattern, dot, tahun, no_claim, nominal
	FROM tblNota 
	JOIN tblDetNota USING(idNota)
	JOIN claim USING(id_claim)
	JOIN barang USING(id_brg)
	WHERE idNota=$idNota";

	$result = $koneksi->query($query);

	$output = array('data' => array());

	if ($result->num_rows > 0) {

		while ($row = $result->fetch_array()) {
			$noSeri = $row[2].'-'.$row[3].'-'.$row[4];
			$output['data'][] = array(
				$row[0],
				$row[1],
				$noSeri,
				$row[5],
				$row[6]);
		}
	}
	$koneksi->close();

	echo json_encode($output);

?>