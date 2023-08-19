<?php 
require_once '../../function/koneksi.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST)
{
	
	$id_klr  = $koneksi->real_escape_string($_POST['id_klr']);
	$id      = $koneksi->real_escape_string($_POST['hapusId']);
	$jml_klr = $koneksi->real_escape_string($_POST['jml_klr']);
	$bulan   = date("m");
	$tahun   = date("Y");

	$cekJmlSld     = $koneksi->query("SELECT id_saldo, saldo_akhir FROM saldo WHERE id = '$id' AND MONTH(tgl)= '$bulan'
									  AND YEAR(tgl)='$tahun'");
	$rowCekJmlSld = $cekJmlSld->fetch_assoc();
	$saldoAsal    = $rowCekJmlSld['saldo_akhir'];
	$id_saldo     = $rowCekJmlSld['id_saldo'];
	$totSaldoAsal = $saldoAsal+$jml_klr;

	$sql_success = "";

	//membuat fungsi transaksi
	$koneksi->begin_transaction();

	$hapusMinus = "DELETE FROM keluar WHERE id_klr = $id_klr";
	if ($koneksi->query($hapusMinus) === TRUE) 
	{
		
		$update = "UPDATE saldo SET saldo_akhir=$totSaldoAsal WHERE id_saldo=$id_saldo";
		if ($koneksi->query($update) === TRUE)
		{
			
			$valid['success']  = true;
			$valid['messages'] = "<strong>Success! </strong>Data Berhasil Dihapus";

			$sql_success .="success";

		}
		else
		{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong>Data Gagal Dihapus. Tabel Saldo(update) Error-AIG-0D09 ".$koneksi->error;

		}

	}
	else
	{

		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong>Data Gagal Dihapus. Table Keluar Error-AIG-0D10 ".$koneksi->error;

	}

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