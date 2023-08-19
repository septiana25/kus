<?php

require_once '../../function/koneksi.php';
require_once '../../function/session.php';
$valid['success'] =  array('success' => false , 'messages' => array());

$id = $koneksi->real_escape_string($_POST['id']); /*"2019-03-15 09:57:07";*/
$query = "UPDATE eventsTgl SET status = '1' WHERE id=$id";

if ($koneksi->query($query) === TRUE) {
	
	$valid['success'] = true;

}

$koneksi->close();

	echo json_encode($valid);
?>