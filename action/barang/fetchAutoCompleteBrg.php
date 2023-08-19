<?php
require_once '../../function/koneksi.php';
if ($_POST) 
{
	$brg = $koneksi->real_escape_string($_POST["brg"]);
	//$post = "a12";
	$sql = "SELECT brg FROM barang WHERE brg LIKE '%".$brg."%' ORDER BY brg ASC";
	//echo $sql;

	$result = $koneksi->query($sql);
	$data = array();
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_array()) {
			$data[] = $row[0];
		}
	}
		$koneksi->close();
		echo json_encode($data);
}
?>