<?php
require_once 'function/koneksi.php';
require_once 'function/session.php';
require_once 'function/setjam.php';

	$nama = $_SESSION['nama'];
	$tgl = date("Y-m-d H:i:s");
	$action ="Menambah ";

	$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, action) VALUES('$nama', '$tgl', '$action')");
?>