<?php

$server   = "localhost";
$username = "root";
$password = "cilisung";
$database = "inventoriKUS";

// Create connection
$koneksi  = new mysqli($server, $username, $password, $database);

// Check connection
if ($koneksi->connect_errno) {
	exit("Failed to connect to MySQL: (" . $koneksi->connect_errno . ") " . $koneksi->connect_error);
}
