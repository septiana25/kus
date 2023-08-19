<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
	
	$NoEtoll      = $_POST['NoEtoll'];
	$nominalSaldo = $_POST['nominalSaldo'];
	$tgl          = date("Y-m-d H:i:s");

	$cekStatus = "SELECT id_toll FROM tblTmbhSaldo WHERE id_toll = $NoEtoll AND stus_tmbh = 0";
	$rest = $koneksi->query($cekStatus);
	if ($rest->num_rows == 1) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error!</strong> Belum Bisa Menambah Saldo Karna Belum Mengajukan TOP UP/POSTING Dimenu Transaksi EToll";		
	}else{

		$simpanTmbh = "INSERT INTO tblTmbhSaldo (id_toll, tmbh_saldo, tgl_tmbh)
										VALUES  ('$NoEtoll', '$nominalSaldo', '$tgl')";
		if ($koneksi->query($simpanTmbh) === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "<strong>Success!</strong> Data Berhasil Disimpan";
		}else{
			$valid['success'] = false;
			$valid['messages'] = "<strong>Error!</strong> Data Gagal Disimpan ".$koneksi->error;
		}

	}
	$koneksi->close();
	echo json_encode($valid);
}else{
	echo "Tidak Berhak Akses";
}


?>