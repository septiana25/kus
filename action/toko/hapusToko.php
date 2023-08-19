<?php
require_once '../../function/koneksi.php';
//require_once '../../function/tgl_indo.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());

if (isset($_POST)) {
	
	$id_toko = $koneksi->real_escape_string($_POST['id_toko']);

	$cek  = $koneksi->query("SELECT toko FROM toko WHERE id_toko = $id_toko");
	$row  = $cek->fetch_assoc();
	$toko = $row['toko'];
	$tgl  = date('Y-m-d H:i:s');
	$nama = $_SESSION['nama'];

	$cekToko = $koneksi->query("SELECT id_toko, toko FROM toko JOIN keluar USING(id_toko) WHERE id_toko = $id_toko");

	if ($cekToko->num_rows > 0) {

		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong> Data Tidak Bisa Dihapus";
	}
	else
	{
		$hapusToko = "DELETE FROM toko WHERE id_toko = $id_toko";

		if ($koneksi->query($hapusToko) == TRUE) {
			
			$valid['success']  = true;
			$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

			$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$toko', 'd')");

		}
		else
		{
			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Tabel Barang Error-AIG-0001 ".$koneksi->error;
		}

	}

$koneksi->close();
echo json_encode($valid);
}


?>