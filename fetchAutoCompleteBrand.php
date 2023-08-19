<?php
include_once 'function/koneksi.php';
$post = $koneksi->real_escape_string($_POST["brand"]);
//$post = "a12";
$sql = "SELECT brand FROM claim WHERE brand LIKE '%".$post."%' GROUP BY brand";

$result = $koneksi->query($sql);
$data = array();
if ($result->num_rows > 0) {
	while ($row = $result->fetch_array()) {
		$data[] = $row[0];
	}
}
	$koneksi->close();
	echo json_encode($data);
?>