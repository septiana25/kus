<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/setjam.php';
	require_once '../../function/session.php';

	$bulan = date("m");
	$tahun = date("Y");
	$sql = "SELECT id_saldo, rak, brg, saldo_awal, saldo_akhir FROM saldo 
			JOIN detail_brg USING(id)
			JOIN barang USING(id_brg) 
			JOIN rak USING(id_rak) 
			WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun";

	$result = $koneksi->query($sql);
	$output = array('data' => array());
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_array()) {
			$output['data'][]= array(
				$row['rak'],
				$row['brg'],
				$row['saldo_awal'],
				$row['saldo_akhir']);
		}
	}
	$koneksi->close();
	echo json_encode($output);