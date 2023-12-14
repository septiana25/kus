<?php
require_once '../../function/koneksi.php';
$post = $koneksi->real_escape_string($_GET["item"]);
//$post = "a12";
$sql = "SELECT id_brg, brg FROM barang WHERE brg LIKE '%" . $post . "%' LIMIT 10";

$result = $koneksi->query($sql);
$data = array();
if ($result->num_rows > 0) {
	while ($row = $result->fetch_array()) {
		$data[] = $row['brg'];
	}
} else {
	$data[] = 'Barang tidak ditemukan';
}
$koneksi->close();
echo json_encode($data);
