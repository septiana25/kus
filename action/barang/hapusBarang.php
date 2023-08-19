<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

	$valid['success'] =  array('success' => false , 'messages' => array());

	if ($_POST) {
		
	$id_brg     = $koneksi->real_escape_string($_POST["id_brg"]);
	$nama       = $_SESSION['nama'];
	$tgl        = date("Y-m-d H:i:s");
	
	$cek_Dbrg   = $koneksi->query("SELECT id_brg, id 
								   FROM detail_masuk 
								     JOIN detail_brg USING(id) 
								     JOIN barang USING(id_brg) 
								   WHERE id_brg = $id_brg
								   UNION ALL 
								   SELECT id_brg, id 
								   FROM detail_keluar
								     JOIN detail_brg USING(id) 
								     JOIN barang USING(id_brg) 
								   WHERE id_brg = $id_brg");
	/*$result     = $koneksi->query($cek_Dbrg);
	$row1       = $result->fetch_assoc();
	$id         = $row1['id']
	
	$cek_Mskbrg = $koneksi->query("SELECT id_msk FROM masuk JOIN detail_masuk USING(id_msk) WHERE id=$id");*/
	
	$cek_brg    = $koneksi->query("SELECT brg FROM barang WHERE id_brg=$id_brg");
	$row        = $cek_brg->fetch_array();
	$brg        = $row[0];
	$ket        ="Hapus Barang ".$brg;

	if ($cek_Dbrg->num_rows > 0)
	{
		$valid['success']  = 'cek_brg';
		$valid['messages'] = "<strong>Error! </strong>Data Tidak Boleh Dihapus. Tabel Barang Error-AIG-0004";
		
	}
	else
	{

		$query = "DELETE FROM barang WHERE id_brg=$id_brg";

		if ($koneksi->query($query) === TRUE) {

			$valid['success']  = true;
			$valid['messages'] = "<strong>Success! </strong> Data Berhasil Dihapus";

			$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'd')");

		}
		else

		{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Dihapus. Tabel Barang Error-AIG-0001 ".$koneksi->error;

		}
	}
		$koneksi->close();

		echo json_encode($valid);
	}

?>