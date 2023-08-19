<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST)
{

	$namaToko  = $koneksi->real_escape_string($_POST['namaToko']);
	$alamat    = $koneksi->real_escape_string($_POST['alamat']);

	$namaLogin = $_SESSION['nama'];
	$ket       = "Toko ".$namaToko;
	$tgl1      = date("Y-m-d H:i:s");

	$cek_toko = $koneksi->query("SELECT toko FROM toko WHERE toko='$namaToko'");

	if ($cek_toko->num_rows == 1)
	{
		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong> Nama Toko Sudah Ada. Tabel Toko Error-AIG-0002 ";
	}

	else
	{
		$insert = "INSERT INTO toko (toko, alamat) VALUES ('$namaToko', '$alamat')";
		
		if ($koneksi->query($insert) === TRUE)
		{
			$valid['success'] = true;
			$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

			$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) 
										  VALUES('$namaLogin', '$tgl1', '$ket', 't')");
		}
		else
		{
			$valid['success'] = false;
			$valid['messages'] = "<strong>Error! </strong>Data Gagal Disimpan. Tabel Toko Error-AIG-0001 ".$koneksi->error;
		}
	}

	$koneksi->close();
	echo json_encode($valid);

}

?>