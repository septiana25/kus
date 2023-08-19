<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

$valid['success'] =  array('success' => false , 'messages' => array());

if ($_POST) {
		
	$id_kat = $koneksi->real_escape_string($_POST['id_kat']);
	$barang = $koneksi->real_escape_string($_POST['editBarang']);
	$editKDbarang = $koneksi->real_escape_string($_POST['editKDbarang']);
	$editNOurut = $koneksi->real_escape_string($_POST['editNOurut']);
	$id_brg = $koneksi->real_escape_string($_POST['editBarangId']);
	$nama   = $_SESSION['nama'];
	$tgl    = date("Y-m-d H:i:s");
	
	$cek_brg = $koneksi->query("SELECT brg FROM barang WHERE id_brg=$id_brg");
	$row     = $cek_brg->fetch_array();
	$brg 	 = $row[0];
	$ket 	 ="Edit Barang ".$brg." Menjadi ".$barang;

		$query = "UPDATE barang SET brg ='$barang', id_kat=$id_kat, nourt=$editNOurut, kdbrg='$editKDbarang' WHERE id_brg=$id_brg";

		if ($koneksi->query($query) === TRUE) {

			$valid['success']  = true;
			$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

			$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'e')");

		}else{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan Tabel Barang Error-AIG-0001".$koneksi->error;
		
		}

		$koneksi->close();

		echo json_encode($valid);
}