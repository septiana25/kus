<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
	$nama      = $koneksi->real_escape_string($_POST['pengirim']);
	$namaLogin = $_SESSION['nama'];
	$ket       = "Pengirim ".$nama;
	$tgl1      = date("Y-m-d H:i:s");

	$cek_pengirim = $koneksi->query("SELECT nama FROM pengirim WHERE nama='$nama'");

	if ($cek_pengirim->num_rows == 1) {

		$valid['success']  = 'cek_pengirim';
		$valid['messages'] = "<strong>Error! </strong> Nama Pengirim Sudah Ada. Tabel Pengirim Error-AIG-0002";

	}else{

		$insert = "INSERT INTO pengirim (nama) VALUES ('$nama')";

		if ($koneksi->query($insert) === TRUE) {

			$valid['success'] = true;
			$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

			$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$namaLogin', '$tgl1', '$ket', 't')");

		}else{

			$valid['success'] = false;
			$valid['messages'] = "<strong>Error! </strong>Data Gagal Disimpan. Tabel Pengirim Error-AIG-0001 ".$koneksi->error;

		}
	}

	$koneksi->close();
	echo json_encode($valid);
}

?>