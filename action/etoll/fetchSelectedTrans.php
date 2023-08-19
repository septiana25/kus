<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

if ($_POST) {
$id = $koneksi->real_escape_string($_POST['id_DetTrans']);
//$id = 3;
$query = "SELECT no_toll, stus_trans, tblDetTransToll.* FROM tblDetTransToll
JOIN tblTransToll USING(id_trans)
JOIN tblEToll USING(id_toll) WHERE id_DetTrans = $id";
$result = $koneksi->query($query);

if ($result->num_rows > 0) {
	$row = $result->fetch_assoc();
}

$koneksi->close();
echo json_encode($row);
}
?>