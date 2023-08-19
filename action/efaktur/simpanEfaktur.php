<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

	$valid['success'] = array('success' => false, 'messages' => array());
	if ($_POST) {
		
		$eFakrur = $_POST["efaktur"];
		$asal    = "Dari Ruko 238";
		$awal    = $_POST["awalFaktur"];
		$faktur  =  $awal.''.$eFakrur;
		$namaLogin = $_SESSION['nama'];
		$ket     = "Efaktur ".$faktur;
		$tgl     = date("Y-m-d");
		$tgl1    = date("Y-m-d H:i:s");

		$cek_faktur = "SELECT no_faktur FROM keluar WHERE no_faktur='$faktur'";
		$result = $koneksi->query($cek_faktur);

		if ($result->num_rows == 1) {
			$valid['success']  = 'cek_faktur';
			$valid['messages'] = "<strong>Error! </strong>No Faktur Sudah Ada";
		}else{
		$insert_efaktur = "INSERT INTO keluar ( no_faktur,
												pengirim,
												tgl)
										VALUES('$faktur',
												'$asal',
												'$tgl')";
		if ($koneksi->query($insert_efaktur) === TRUE) {
			$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$namaLogin', '$tgl1', '$ket', 't')");
			$valid['success']  = true;
			$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
		}else{
			$valid['success']  = false;
	 		$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;
		}
		
		}
		$koneksi->close();
		echo json_encode($valid);
	}
?>