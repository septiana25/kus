<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$valid['success'] =  array('success' => false , 'messages' => array());

	if ($_POST) {

	$id_toko = $koneksi->real_escape_string($_POST['editIdToko']);
	$toko    = $koneksi->real_escape_string($_POST['editNamaToko']);
	$almt    = $koneksi->real_escape_string($_POST['editAlamat']);
	
	$cekKat  = $koneksi->query("SELECT toko, alamat FROM toko WHERE id_toko = $id_toko");
	$rowKat  = $cekKat->fetch_assoc();
	$toko1    = $rowKat['toko'];
	$alamat  = $rowKat['alamat'];
	$nama    = $_SESSION['nama'];
	$tgl     = date("Y-m-d H:i:s");
	$ket     ="Edit Toko ".$toko1." Menjadi ".$toko." Dan ".$alamat." Menjadi ".$almt;

	$query  = "UPDATE toko SET toko ='$toko', alamat = '$almt' WHERE id_toko = $id_toko";

	if ($koneksi->query($query) === TRUE) {

		$valid['success']  = true;
		$valid['messages'] = "<strong>Success! </strong>Data Berhasil Diubah";

		$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'e')");

	}else{
		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diubah. Tabel Toko Error-AIG-0001 ".$koneksi->error;
	}

	$koneksi->close();

	echo json_encode($valid);
	}