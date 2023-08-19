<?php

require_once '../../function/koneksi.php';
require_once '../../function/session.php';
$valid['success'] =  array('success' => false , 'messages' => array());
$title = $koneksi->real_escape_string($_POST['title']); /*"tesss";*/
$start = $koneksi->real_escape_string($_POST['start']); /*"2019-03-15 09:57:07";*/
$end = $koneksi->real_escape_string($_POST['end']); /*"2019-03-15 09:57:07";*/
$id = $koneksi->real_escape_string($_POST['id']); /*"2019-03-15 09:57:07";*/
$query = "UPDATE eventsTgl SET start_event = '$start', end_event = '$end' WHERE id=$id";

if ($koneksi->query($query) === TRUE) {
	
	$valid['success'] = true;

}

$koneksi->close();

	echo json_encode($valid);
?>