<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../../function/fungsi_rupiah.php';

	$valid['success'] =  array('success' => false , 'messages' => array());

	if ($_POST) {
		
	$editNoEtoll    = $koneksi->real_escape_string($_POST['editNoEtoll']);
	$editRute       = $koneksi->real_escape_string($_POST['editRute']);
	$editRuteAkhir  = $koneksi->real_escape_string($_POST['editRuteAkhir']);
	$editBayar      = $koneksi->real_escape_string($_POST['editBayar']);
	$editKeterangan = $koneksi->real_escape_string($_POST['editKeterangan']);
	$editIdDetTrans = $koneksi->real_escape_string($_POST['editIdDetTrans']);
	

		$cekSaldoToll = $koneksi->query("SELECT saldoTambah-saldoKurang AS total
				FROM(
				SELECT id_toll, no_toll, SUM(IFNULL(bayar, 0)) AS saldoKurang FROM tblDetTransToll
				JOIN tblTransToll USING(id_trans)
				RIGHT JOIN tblEToll USING(id_toll)
				GROUP BY no_toll
				) a
				LEFT JOIN(
				SELECT id_toll, SUM(IFNULL(tmbh_saldo, 0)) AS saldoTambah FROM tblTmbhSaldo
				RIGHT JOIN tblEToll USING(id_toll)
				GROUP BY no_toll
				)b ON a.id_toll=b.id_toll WHERE a.no_toll = '$editNoEtoll'");
		$rowCekSaldo = $cekSaldoToll->fetch_assoc();
		$totalSaldo = $rowCekSaldo['total'];

		if ($totalSaldo < $editBayar){

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Saldo E-Toll Kurang, Sisa Tinggal ".format_rupiah($totalSaldo);

		}else{

			$query = "UPDATE tblDetTransToll SET rute ='$editRute', ruteAkhir ='$editRuteAkhir', bayar=$editBayar, ket='$editKeterangan' WHERE id_DetTrans=$editIdDetTrans";

			if ($koneksi->query($query) === TRUE) {
				//$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'e')");
				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
			}else{
				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan ".$koneksi->error;
			}

		}
		

		$koneksi->close();

		echo json_encode($valid);
	}