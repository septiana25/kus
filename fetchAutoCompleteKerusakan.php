<?php
include_once 'function/koneksi.php';
$post = $koneksi->real_escape_string($_POST["kerusakan"]);
//$post = "a12";
$sql = "SELECT kerusakan FROM claim WHERE kerusakan LIKE '%".$post."%' GROUP BY kerusakan";

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