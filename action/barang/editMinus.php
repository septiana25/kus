<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) 
{
	
	$id_det_klr = $koneksi->real_escape_string($_POST['editIdDetKlr']);
	$ket        = $koneksi->real_escape_string($_POST['editketMinus']);
	$jml_klr    = $koneksi->real_escape_string($_POST['editJmlMinus']);
	$bulan 		= date('m');
	$tahun 		= date('Y');
	$cariMinus = $koneksi->query("SELECT id_det_klr, id_klr, id, jml_klr, ket
								  FROM detail_keluar
								  JOIN keluar USING(id_klr)
								  JOIN detail_brg USING(id)
								  WHERE id_det_klr = $id_det_klr");
	$rowCariMinus = $cariMinus->fetch_assoc();
	$jml_klr1 = $rowCariMinus['jml_klr'];
	$ket1     = $rowCariMinus['ket'];
	$id       = $rowCariMinus['id'];
	$idKlr    = $rowCariMinus['id_klr'];

	$cekJmlSld     = $koneksi->query("SELECT id_saldo, saldo_akhir FROM saldo WHERE id = '$id' AND MONTH(tgl)= '$bulan'
									  AND YEAR(tgl)='$tahun'");
	$rowCekJmlSld  = $cekJmlSld->fetch_assoc();
	$saldoAsal 	   = $rowCekJmlSld['saldo_akhir'];
	$id_saldoAsal  = $rowCekJmlSld['id_saldo'];
	$totSaldoAsal  = $saldoAsal+$jml_klr1;

	//membuat fungsi transaksi
	$koneksi->begin_transaction();

	$sql_success = "";

	if ( $totSaldoAsal >= $jml_klr )
	{
		
		$updateDetKlr = "UPDATE detail_keluar SET jml_klr = $jml_klr, ket = '$ket' WHERE id_det_klr = $id_det_klr";
		if ($koneksi->query($updateDetKlr) === TRUE)
		{
			
			$saldoSisa =  $totSaldoAsal-$jml_klr;

			$updateSaldoSisa = "UPDATE saldo SET saldo_akhir = $saldoSisa WHERE id_saldo = $id_saldoAsal";
			if ($koneksi->query($updateSaldoSisa) === TRUE)
			{
				
				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong> Data Berhasil Disimpan";

				$sql_success .="success";

			}
			else
			{

				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0024 ".$koneksi->error;

			}

		}
		else
		{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Keluar Error-AIG-0025 ".$koneksi->error;

		}

	}
	else
	{

		$valid['success']  = false;
		$valid['messages'] = 'Jumlah Terlalu Besar. Error-AIG-0006 Sisa ' .$totSaldoAsal; 

	}

/*	if ( $jml_klr1 != $jml_klr1 AND $ket != $ket1 ) {
		#lakukan edit 2 2 nya.

	}
	elseif ( $jml_klr1 != $jml_klr1 AND $ket = $ket1 ) {
		# lakukan edit jmlh_klr aja

	}
	elseif ( $jml_klr1 = $jml_klr1 AND $ket =! $ket1 ) {
		# lakukan edit ketearangan aja

	}
	else
	{
		$valid['success']  = "TdkEdit";
		$valid['messages'] = "<strong>Info!</strong> Tidak Ada Perubahaan";
	}*/

	/*====================< Fungsi Rollback dan Commit >========================*/
		if ($sql_success)
		{

			$koneksi->commit();//simpan semua data simpan

		}
		else
		{

			$koneksi->rollback();//batal semua data simpan

		}
	/*====================< Fungsi Rollback dan Commit >========================*/

	$koneksi->close();
	echo json_encode($valid);

}
?>