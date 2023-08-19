<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$valid['success'] =  array('success' => false , 'messages' => array());

	if ($_POST) {

	$id_kat = $koneksi->real_escape_string($_POST['editKategoriId']);
	$kat 	= $koneksi->real_escape_string($_POST['kat']);

	$cekKat = $koneksi->query("SELECT kat FROM kat WHERE id_kat= $id_kat");
	$rowKat = $cekKat->fetch_assoc();
	$kat1   = $rowKat['kat'];
	$nama   = $_SESSION['nama'];
	$tgl    = date("Y-m-d H:i:s");
	$ket 	="Edit Kategori ".$kat1." Menjadi ".$kat;

	$query  = "UPDATE kat SET kat ='$kat' WHERE id_kat=$id_kat";

	if ($koneksi->query($query) === TRUE) {

		$valid['success']  = true;
		$valid['messages'] = "<strong>Success! </strong>Data Berhasil Diubah";

		$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'e')");

	}else{
		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diubah. Tabel Kategori Error-AIG-0001 ".$koneksi->error;
	}

	$koneksi->close();

	echo json_encode($valid);
	}