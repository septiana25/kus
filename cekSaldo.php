<?php
	require_once 'function/koneksi.php';
	require_once 'function/setjam.php';
	// require_once '../../function/session.php';

	// $Select = "SELECT id, saldo_akhir FROM saldo WHERE (MONTH(tgl)=8 AND YEAR(tgl)=2017) AND (";

	$insert ="INSERT INTO tblLimit (id_brg, btsLimit) VALUES ";
	//$queryCekSaldo = "SELECT id, tgl FROM saldo WHERE MONTH(tgl)=8 AND YEAR(tgl)=2017";
	$queryCekSaldo = "SELECT id_brg FROM barang ORDER BY id_brg ASC";
	$resCek = $koneksi->query($queryCekSaldo);
	while ($row = $resCek->fetch_array()) {
		$id= $row[0];
		// $tgl= $row[1];
		$limit=5;
		// echo $id." ".$tgl."<br/>";
		// $Select .= "id = " .$id." AND "."saldo_akhir < ".$limit." OR ";
		$insert .= "('".$id."','".$limit."'),";
	}

	$insert = rtrim($insert, ', ');

	echo $insert;

?>