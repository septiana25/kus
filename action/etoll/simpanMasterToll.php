<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$valid['success'] = array('success' => false, 'message' => array());

if ($_POST) {
	$noEToll   = $koneksi->real_escape_string($_POST['noEToll']);
	$pemegang  = $koneksi->real_escape_string($_POST['pemegang']);
	$nopol     = $koneksi->real_escape_string($_POST['nopol']);
	$saldoAwal = $koneksi->real_escape_string($_POST['saldoAwal']);

	$cekNoToll = $koneksi->query("SELECT no_toll FROM tblEToll WHERE no_toll = '$noEToll'");

	//cek No E-Toll di tabel tblEToll
	if ($cekNoToll->num_rows == 1) {
		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong> No E-Toll Sudah Ada ";		
	}else{
		$querySimpn = "INSERT INTO tblEToll (no_toll,pemegang, no_pol) 
									VALUES  ('$noEToll','$pemegang','$nopol')";
		if ($koneksi->query($querySimpn) === TRUE) {
			$id = $koneksi->insert_id;
			$insertTmbh = $koneksi->query("INSERT INTO tblTmbhSaldo (id_toll, tmbh_saldo, tgl_tmbh)VALUES('$id','0','')");

			if ($koneksi->query("INSERT INTO tblTransToll (id_toll, stus_trans) VALUES ('$id', '1')")) {
				$id_trans = $koneksi->insert_id;
				$insetDetTran = $koneksi->query("INSERT INTO tblDetTransToll (id_trans, rute, ruteAkhir, bayar, jam, ket, tgl_trans) VALUES ('$id_trans', '-','-', '0', '00:00:00', '-', '0000-00-00')");
				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";	
			}else{
				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan ".$koneksi->error;	
			}			
			
		}else{
			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan ".$koneksi->error;			
		}
	}

	$koneksi->close();

	echo json_encode($valid);

}

?>