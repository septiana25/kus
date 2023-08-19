<?php

require_once '../../function/koneksi.php';
require_once '../../function/session.php';

$query = "SELECT * FROM eventsTgl WHERE status = '0' ORDER BY id";
$statment = $koneksi->query($query);
$result = $statment->fetch_all(MYSQL_ASSOC);

foreach ($result as $row) {
	
	$data[] =  array(
		'id'    =>  $row["id"],
		'title' =>  $row["title"],
		'start' =>  $row["start_event"],
		'end'   =>  $row["end_event"]
	);
}
echo json_encode($data);
?>