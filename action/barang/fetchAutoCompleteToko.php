<?php
require_once '../../function/koneksi.php';
if ($_POST) 
{
	$namatoko = $koneksi->real_escape_string($_POST["namatoko"]);
	//$post = "a12";
	$sql = "SELECT toko FROM toko WHERE toko LIKE '%".$namatoko."%' ORDER BY toko ASC";
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