<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$valid['success'] =  array('success' => false , 'messages' => array());
	
if ($_POST) {
		
	$namatoko     = $_POST["namatoko"];
	$diskon      = $_POST["top"];
	$ket      = $_POST["ket"];
	//$barang      = 'GTX12';
	$nama = $_SESSION['nama'];
	$tgl = date("Y-m-d H:i:s");
	$ket1 ="Baru ";
				
				$query = "INSERT INTO tblOrder (toko, top, sales, ketOrder)VALUES('$namatoko', '$diskon', '$nama', '$ket')";

				if ($koneksi->query($query) === TRUE)
				{
					$last_id = $koneksi->insert_id;
					$valid['success']  = true;
					$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

					$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket1', 't')");

				}
				else
				{
					$valid['success']  = false;
					$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Tabel Barang Error-AIG-0001 ".$koneksi->error;
				}
				
		$koneksi->close();

		echo json_encode(['id'=>$last_id]);
}

?>