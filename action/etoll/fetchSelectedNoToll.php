<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$id = $_POST['id_toll'];
//$id = 3;
$query = "SELECT * FROM tblEToll WHERE id_toll = $id";
$result = $koneksi->query($query);

if ($result->num_rows > 0) {
	$row = $result->fetch_assoc();
}

$koneksi->close();
echo json_encode($row);
?>