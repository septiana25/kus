<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';


$valid['success'] = array('success' => false, 'messages' => array());
if ($_POST) {

	$id_trans  = $koneksi->real_escape_string($_POST['id_trans']);
	$id_tmbh   = $koneksi->real_escape_string($_POST['id_tmbh']);
	$saldoAwal = $koneksi->real_escape_string($_POST['saldoAwal']);

	$simpanPosting = "INSERT INTO tblPostingEToll (id_trans, id_tmbh, s_awal) 
							VALUES ('$id_trans', '$id_tmbh', '$saldoAwal')";
							
	if ($koneksi->query($simpanPosting) === TRUE) {
		$updateTrans   = $koneksi->query("UPDATE tblTransToll SET stus_trans = 1 WHERE id_trans = $id_trans");
		$updateTmbh    = $koneksi->query("UPDATE tblTmbhSaldo SET stus_tmbh = 1  WHERE id_tmbh  = $id_tmbh");

		$valid['success']  = true;
		$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan ";		
	}else{
		$updateTrans   = $koneksi->query("UPDATE tblTransToll SET stus_trans = 0 WHERE id_trans = $id_trans");
		$updateTmbh    = $koneksi->query("UPDATE tblTmbhSaldo SET stus_tmbh = 0  WHERE id_tmbh  = $id_tmbh");

		$valid['success']  = false;
		$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;	
	}

$koneksi->close();

echo json_encode($valid);
}

?>