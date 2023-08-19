<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
//require_once '../../function/setjam.php';

	$valid['success'] =  array('success' => false , 'messages' => array());

	if ($_POST) {
		
	$id_rak = $koneksi->real_escape_string($_POST['editRak']);
	$rak 	= $koneksi->real_escape_string($_POST['rak']);

	$cekRak = $koneksi->query("SELECT rak FROM rak WHERE id_rak=$id_rak");
	$rowRak = $cekRak->fetch_assoc();
	$rak1   = $rowRak['rak']
	$ket    = "Edit ".$rak1." Menjadi ".$rak;
	$nama   = $_SESSION['nama'];
	$tgl    = date("Y-m-d H:i:s");

	// $cek_brg = $koneksi->query("SELECT id_brg FROM barang WHERE brg='$barang'");
	// $row     = $cek_brg->fetch_array();

		$query = "UPDATE rak SET rak ='$rak' WHERE id_rak=$id_rak";

		if ($koneksi->query($query) === TRUE) {

			$valid['success']  = true;
			$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

			$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'e')");

		}else{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Tabel Rak Error-AIG-0001 ".$koneksi->error;
		
		}

		$koneksi->close();

		echo json_encode($valid);
	}