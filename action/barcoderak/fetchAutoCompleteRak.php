<?php
require_once '../../function/koneksi.php';
$post = $koneksi->real_escape_string($_GET["rak"]);
//$post = "a12";
$sql = "SELECT rak FROM rak WHERE rak LIKE '%" . $post . "%' LIMIT 10";

$result = $koneksi->query($sql);
$data = array();
if ($result->num_rows > 0) {
	while ($row = $result->fetch_array()) {
		$data[] = $row['rak'];
	}
} else {
	$data[] = 'Rak tidak ditemukan';
}
$koneksi->close();
echo json_encode($data);
