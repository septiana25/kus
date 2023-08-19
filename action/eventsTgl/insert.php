<?php

require_once '../../function/koneksi.php';
require_once '../../function/session.php';
$valid['success'] =  array('success' => false , 'messages' => array());
$title = $koneksi->real_escape_string($_POST['title']); /*"tesss";*/
$start = $koneksi->real_escape_string($_POST['start']); /*"2019-03-15 09:57:07";*/
$end = $koneksi->real_escape_string($_POST['end']); /*"2019-03-15 09:57:07";*/
$pembuat = $_SESSION['nama']; /*"Ian";*/
$query = "INSERT INTO eventsTgl (title, start_event, end_event, pembuat) 
		  VALUES ('$title','$start','$end','$pembuat')";

if ($koneksi->query($query) === TRUE) {
	
	$valid['success'] = true;

}

$koneksi->close();

	echo json_encode($valid);
?>