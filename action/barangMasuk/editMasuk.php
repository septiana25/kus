<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

	$editIdDetMsk = $koneksi->real_escape_string($_POST["editIdDetMsk"]);

	$editKet      = $koneksi->real_escape_string($_POST["editKet"]);

	$update = "UPDATE detail_masuk SET ket = '$editKet' WHERE id_det_msk = $editIdDetMsk ";

	if($koneksi->query($update) === TRUE) {
		$valid['success']  = true;
		$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
	}else{
		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong>Data Gagal Disimpan Di Detail Masuk. Error-AIG-0001 ".$koneksi->error;
	}

	$koneksi->close();

	echo json_encode($valid);
	
}