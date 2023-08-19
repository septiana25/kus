<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$valid['success'] = array('success' => false, 'messages' =>array());

	$id_klr = $_POST['id_klr'];

	if ($id_klr) {
		
		$sql = "DELETE FROM keluar WHERE id_klr=$id_klr";
		if ($koneksi->query($sql) === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "<strong>Data Berhasil Dihapus</strong>";
		}else{
			$valid['success'] = false;
			$valid['messages'] = "<strong>Data Gagal Dihapus </strong>".$koneksi->error;
		}

		$koneksi->close();
		echo json_encode($valid);
	}
?>