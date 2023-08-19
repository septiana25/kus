<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
	
	$editNoEToll  = $koneksi->real_escape_string($_POST['editNoEToll']);
	$pemegang     = $koneksi->real_escape_string($_POST['editPemegang']);
	$nopol        = $koneksi->real_escape_string($_POST['editNopol']);
	$editNoTollId = $koneksi->real_escape_string($_POST['editNoTollId']);

	$query = "UPDATE tblEToll SET no_toll = '$editNoEToll', pemegang = '$pemegang', no_pol = '$nopol' WHERE id_toll=$editNoTollId";
	if ($koneksi->query($query)) {
		$valid['success'] = true;
		$valid['messages'] = "<strong>Success! </strong> Data Berhasil Disimpan";
	}else{
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan";
	}

$koneksi->close();

echo json_encode($valid);
}

?>