<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$valid['success'] =  array('success' => false , 'messages' => array());
	
if ($_POST) {
		
	$brg     = $_POST["brg"];
	$qty      = $_POST["qty"];
	$id_order      = $_POST["id_order"];
	$ketDet      = $_POST["ketDet"];
	$diskon      = $_POST["diskon"];
	$harga      = $_POST["harga"];
	//$barang      = 'GTX12';
	$nama = $_SESSION['nama'];
	$tgl = date("Y-m-d H:i:s");
	$ket ="Baru ";
				
				$query = "INSERT INTO detail_order (id_order, nama_brg, qty, ket_Det, diskon, harga)VALUES('$id_order', '$brg', '$qty', '$ketDet', '$diskon', '$harga')";

				if ($koneksi->query($query) === TRUE)
				{
					$last_id = $koneksi->insert_id;
					$valid['success']  = true;
					$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

					$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 't')");

				}
				else
				{
					$valid['success']  = false;
					$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Tabel Barang Error-AIG-0001 ".$koneksi->error;
				}
				
		$koneksi->close();

		echo json_encode($valid);
}

?>