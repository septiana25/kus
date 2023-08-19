<?php
	require_once '../../function/koneksi.php';	
	require_once '../../function/session.php';

	$valid['success'] = array('success' => false, 'messages' => array());

	if ($_POST) {
		$id_brg = $_POST['editBarangId'];
		$setLimit = $_POST['setLimit'];
		// echo $id_brg;

		$cekLimit = "SELECT idLimit, id_brg FROM tblLimit WHERE id_brg=$id_brg";
		$resLimit = $koneksi->query($cekLimit);

		if ($resLimit->num_rows > 0) {
			$updateLimit = "UPDATE tblLimit SET btsLimit =$setLimit WHERE id_brg=$id_brg";
			if ($koneksi->query($updateLimit)) {
				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
			}else{
				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong>Data Gagal Disimpan ".$koneksi->error;
			}
			
		}else{
			$simpanLimit = "INSERT INTO tblLimit (id_brg, btsLimit) VALUES ('$id_brg', '$setLimit')";
			if ($koneksi->query($simpanLimit)) {
				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
			}else{
				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong>Data Gagal Disimpan ".$koneksi->error;
			}
		}

		$koneksi->close();

		echo json_encode($valid);
	}
?>