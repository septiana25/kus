<?php

require_once '../function/koneksi.php';
$query = $koneksi->query("SELECT * FROM saldo WHERE MONTH(tgl)=5");
while ($row = $query->fetch_array()) {
	$id = $row[0];
	$insert = "INSERT INTO tes (id) VALUES ('$id')";
	if ($koneksi->query($insert) === TRUE) {
		echo "Success Simapan <br>";
	}else{
		echo "Error";
	}
}

?>