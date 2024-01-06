<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

	$id_det_msk = $koneksi->real_escape_string($_POST['id_det_msk']);

	$cek_brg     = $koneksi->query("SELECT id_msk, id, jml_msk FROM detail_masuk LEFT JOIN masuk USING(id_msk)
									WHERE id_det_msk=$id_det_msk");
	$rowBrg      = $cek_brg->fetch_array();
	$id          = $rowBrg['id'];
	$id_msk 	 = $rowBrg['id_msk'];

	$cek_msk     = $koneksi->query("SELECT id, jml_msk FROM detail_masuk JOIN masuk USING(id_msk) 
									WHERE id_msk=$id_msk");

	$cek_saldo   = $koneksi->query("SELECT id_saldo, saldo_akhir FROM saldo WHERE id=$id ORDER BY id_saldo DESC LIMIT 0,1");
	$rowSaldo    = $cek_saldo->fetch_array();
	$id_saldo    = $rowSaldo['id_saldo'];
	$total       = $rowSaldo['saldo_akhir'];
	$total_akhir = $total - $rowBrg['jml_msk'];

	$sql_success = "";

	//membuat fungsi transaksi
	$koneksi->begin_transaction();

	$insert = "DELETE FROM detail_masuk WHERE id_det_msk=$id_det_msk";

	if ($koneksi->query($insert) === TRUE) {

		$update = "UPDATE saldo SET saldo_akhir=$total_akhir WHERE id_saldo=$id_saldo";

		if ($koneksi->query($update) === TRUE) {

			$valid['success']  = true;
			$valid['messages'] = "<strong>Success </strong> Data Berhasil Dihapus";

			$sql_success .= "success";
		} else {

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Dihapus (Saldo Gagal Update). Error-AIG-0A23 " . $koneksi->error;
		}
	} else {

		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Dihapus Di Tabel Masuk. Error-AIG-0A24 " . $koneksi->error;
	}



	/*====================< Fungsi Rollback dan Commit >========================*/
	if ($sql_success) {

		$koneksi->commit(); //simpan semua data simpan

	} else {

		$koneksi->rollback(); //batal semua data simpan

	}
	/*====================< Fungsi Rollback dan Commit >========================*/

	$koneksi->close();

	echo json_encode($valid);
}
