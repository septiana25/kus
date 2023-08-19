<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';
	require_once '../../function/setjam.php';

	$id_claim  = $koneksi->real_escape_string($_POST['editIdClaim']);
	$keputusan = $koneksi->real_escape_string($_POST['editKeputusan']);

	if ($keputusan == 'Tolak') {
		$nominal = 0;
	}else{
		$nominal   = $koneksi->real_escape_string($_POST['editNominal']);
	}

	$cekClaim = "SELECT pengaduan FROM claim WHERE id_claim =$id_claim";
	$resClaim = $koneksi->query($cekClaim);
	$rowClaim = $resClaim->fetch_assoc();


	$nama = $_SESSION['nama'];
	$tgl = date("Y-m-d H:i:s");
	$ket ="No pengaduan ".$rowClaim['pengaduan']." Edit ".$keputusan." & ".$nominal;

	$valid['success'] =  array('success' => false , 'messages' => array());

	if ($_POST) {

	$queryEditClaim = "UPDATE claim SET keputusan= '$keputusan', nominal= '$nominal' WHERE id_claim=$id_claim";
		
	if ($koneksi->query($queryEditClaim) === TRUE) {

		$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'e')");

		$valid['success']  = true;
		$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
	}else{
		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan ".$koneksi->error;
	}

	$koneksi->close();

	echo json_encode($valid);

	}
?>