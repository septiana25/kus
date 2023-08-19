<?php
include_once '../../function/koneksi.php';
include_once '../../function/session.php';
include_once '../../function/setjam.php';

$valid['success'] = array('success' => false, 'messages' =>array());

if ($_POST) {
	
	$id_claim = $koneksi->real_escape_string($_POST['id_claim']);

	$cekClaim = "SELECT pengaduan FROM claim WHERE id_claim =$id_claim";
	$resClaim = $koneksi->query($cekClaim);
	$rowClaim = $resClaim->fetch_assoc();


	$nama = $_SESSION['nama'];
	$tgl = date("Y-m-d H:i:s");
	$ket ="Claim No pengaduan ".$rowClaim['pengaduan'];

	$queryDet = "DELETE FROM claim WHERE id_claim=$id_claim";

	if ($koneksi->query($queryDet)) {
		$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'd')");
		$valid['success']  = true;
		$valid['messages'] = '<strong>Success! </strong Data Berhasil Disimpan';
	}else{
		$valid['success']  = false;
		$valid['messages'] = '<strong>Error! </strong Data Gagal Disimpan '.$koneksi->error;
	}

	$koneksi->close();

	echo json_encode($valid);

}

?>