<?php
include_once 'function/koneksi.php';
$post = $koneksi->real_escape_string($_POST["pattern"]);
//$post = "a12";
$sql = "SELECT pattern FROM claim WHERE pattern LIKE '%".$post."%' GROUP BY pattern";

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