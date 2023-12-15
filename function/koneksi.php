<?php

$server   = "localhost";
$username = "root";
$password = "cilisung";
$database = "inventoriKUS";

//db connection
$koneksi  = new mysqli($server, $username, $password, $database);
//check connection
if ($koneksi->connect_error) {
	die("Koneksi Gagal : " . $koneksi->$connect_error);
} else {
	//echo "Koneksi Berhasil";
}
