<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$valid['success'] =  array('success' => false , 'messages' => array());
	
if ($_POST) {
		
	$id_order     = $_POST["id_order"];
	//$barang      = 'GTX12';
	$nama = $_SESSION['nama'];
	$tgl = date("Y-m-d H:i:s");
	$ket1 ="Baru ";
				
				$query = "UPDATE tblOrder SET status = 'SELESAI' WHERE id_order= $id_order";

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

		echo json_encode($valid);
}

?>