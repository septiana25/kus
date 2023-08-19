<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/setjam.php';
	// require_once '../../function/session.php';


	$brg = "SELECT id_brg, brg FROM barang";
	$resBarang = $koneksi->query($brg);
	while ($dataBrg = $resBarang->fetch_array()) {
		$data['id_brg'] = $dataBrg[0];
		$data['brg'] = $dataBrg[1];
		echo json_encode($data);

	}
	$rak = "SELECT id_rak, rak FROM rak";
	$resRak = $koneksi->query($rak);
	while ($dataRak = $resRak->fetch_array()) {
		$dataR['id_rak'] = $dataRak[0];
		$dataR['rak'] = $dataRak[1];
		echo json_encode($dataR);

	}

/*	$rak = "SELECT id_rak, rak FROM rak";
	$resRak = $koneksi->query($rak);
	$dataRak = $resRak->fetch_all();
	$koneksi->close();*/


	//echo json_encode($dataRak);
?>