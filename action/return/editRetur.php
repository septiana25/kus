<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

	$editId  = $koneksi->real_escape_string($_POST["editId"]);
	
	$editKet = $koneksi->real_escape_string($_POST["editKet"]);

	$update = "UPDATE detail_masuk SET ket = '$editKet' WHERE id_det_msk = $editId ";

	if($koneksi->query($update) === TRUE) {
		$valid['success']  = true;
		$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
	}else{
		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong>Data Gagal Dihapus  ".$koneksi->error;
	}

	$koneksi->close();

	echo json_encode($valid);
	
}