<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$valid['success'] =  array('success' => false , 'messages' => array());

	if ($_POST) {

	$id_kat = $koneksi->real_escape_string($_POST['id_kat']);

	$cekKat = $koneksi->query("SELECT kat FROM kat WHERE id_kat = $id_kat");
	$rowKat = $cekKat->fetch_assoc();
	$kat    = $rowKat['kat'];
	$nama   = $_SESSION['nama'];
	$tgl    = date("Y-m-d H:i:s");
	$ket    ="Hapus Kategori ".$kat;

	$cek_kat =$koneksi->query("SELECT * FROM barang WHERE id_kat = $id_kat");

	if ($cek_kat->num_rows >= 1) {

		$valid['success']  = 'cek_kat';
		$valid['messages'] = "<strong>Error! </strong>Data Tidak Boleh Dihapus. Tabel Kategori Error-AIG-0004";

	}
	else
	{

		$query = "DELETE FROM kat WHERE id_kat= $id_kat";

		if ($koneksi->query($query) === TRUE) {

			$valid['success']  = true;
			$valid['messages'] = "<strong>Data Berhasil Dihapus</strong>";

			$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'd')");


		}else{
			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Dihapus. Tabel Kategori Error-AIG-0001 ".$koneksi->error;
		}
	}

	$koneksi->close();

	echo json_encode($valid);
	}