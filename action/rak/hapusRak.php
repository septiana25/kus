<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
//require_once '../../function/setjam.php';

	$valid['success'] =  array('success' => false , 'messages' => array());

	if ($_POST) {
		
	$id_rak = $koneksi->real_escape_string($_POST['id_rak']);

	$cekRak = $koneksi->query("SELECT rak FROM rak WHERE id_rak=$id_rak");
	$rowRak = $cekRak->fetch_assoc();
	$rak    = $rowRak['rak'];
	$ket    = "Hapus ".$rak;
	$nama   = $_SESSION['nama'];
	$tgl    = date("Y-m-d H:i:s");


	$cek_brg = $koneksi->query("SELECT id_rak FROM detail_brg WHERE id_rak=$id_rak");
	if ($cek_brg->num_rows >=1) {
		$valid['success']  = 'cek_rak';
		$valid['messages'] = "<strong>Error! </strong> Data Tidak Bisa Dihapus. Tabel Rak Error-AIG-0004";
	}else{

		$query = "DELETE FROM rak WHERE id_rak=$id_rak";

		if ($koneksi->query($query) === TRUE) {
			$valid['success']  = true;
			$valid['messages'] = "<strong>Data Berhasil Dihapus</strong>";

			$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'd')");

		}else{
			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Dihapus. Tabel Rak Error-AIG-0001 ".$koneksi->error;
		}
	}

		$koneksi->close();

		echo json_encode($valid);
	}