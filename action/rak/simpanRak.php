<?php

require_once '../../function/koneksi.php';
require_once '../../function/session.php';

	$valid['success'] =  array('success' => false , 'messages' => array());

	if ($_POST) {
		
		$rak       = $koneksi->real_escape_string($_POST['rak']);
		$namaLogin = $_SESSION['nama'];
		$ket       = "Rak ".$rak;
		$tgl1      = date("Y-m-d H:i:s");

		$cek_rak = $koneksi->query("SELECT rak FROM rak WHERE rak='$rak'");

		if ($cek_rak->num_rows == 1) {
			$valid['success']  = 'cek_rak';
			$valid['messages'] = "<strong>Error! </strong>Lokasi Rak Sudah Ada. Tabel Rak Error-AIG-0002";
		}else{
			$query = "INSERT INTO rak (rak) VALUES('$rak')";

			if ($koneksi->query($query) === TRUE) {

				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

				$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) 
											  VALUES('$namaLogin', '$tgl1', '$ket', 't')");

			}else{

				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Tabel Rak Error-AIG-0001 ".$koneksi->error;
			
			}
		}

		$koneksi->close();

		echo json_encode($valid);
	}
?>