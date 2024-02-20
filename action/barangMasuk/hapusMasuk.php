<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';
require_once '../class/masuk.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
	$detailSaldoClass = new DetailSaldo($koneksi);
	$masukClass = new Masuk($koneksi);

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
	$jml		 = $rowBrg['jml_msk'];
	$total_akhir = $total - $rowBrg['jml_msk'];

	$sql_success = "";

	//membuat fungsi transaksi
	$koneksi->begin_transaction();

	$insert = "DELETE FROM detail_masuk WHERE id_det_msk=$id_det_msk";

	if ($koneksi->query($insert) === TRUE) {

		$update = "UPDATE saldo SET saldo_akhir=$total_akhir WHERE id_saldo=$id_saldo";

		$result =  handleIdDetailSaldo($masukClass, $detailSaldoClass, $id_det_msk, $id);
		$idDetailSaldo = $result['idDetailSaldo'];
		$jumlahSaldo = $result['jumlah'];
		$updateDetailSaldo = handleUpdateDetailSaldo($detailSaldoClass, $masukClass, $idDetailSaldo, $id_det_msk, $jml, $jumlahSaldo);

		if ($koneksi->query($update) === TRUE && $updateDetailSaldo['success']) {

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

function handleIdDetailSaldo($masukClass, $detailSaldoClass, $idDetMasuk, $id)
{
	global $valid;
	$checkTahunProd = $masukClass->getDetailMasukTahunProd($idDetMasuk);

	if ($checkTahunProd->num_rows === 0) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Tidak Ditemukan. Di Tabel Tahun Prod Keluar";
		return $valid;
	}

	$resultTahunProd = $checkTahunProd->fetch_array();
	$tahunprod = $resultTahunProd['tahunprod'];
	$checkDetailSaldo = $detailSaldoClass->getDetailSaldoByidAndYearProd($id, $tahunprod);

	if ($checkDetailSaldo->num_rows === 0) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Tidak Ditemukan. Di Tabel Detail Saldo";
		return $valid;
	}

	$resultDetailSaldo = $checkDetailSaldo->fetch_array();

	return ['idDetailSaldo' => $resultDetailSaldo['id_detailsaldo'], 'jumlah' => $resultDetailSaldo['jumlah']];
}

function handleUpdateDetailSaldo($detailSaldoClass, $masukClass, $idDetailSaldo, $idDetMasuk, $jml, $jumlahSaldo)
{

	$totalJumlah = $jumlahSaldo - $jml;
	$updateDetailSaldo = $detailSaldoClass->update($idDetailSaldo, $totalJumlah);
	$deleteDetailMasukTahunProd = $masukClass->deleteDetailMasukTahunProd($idDetMasuk);

	if ($updateDetailSaldo['success'] && $deleteDetailMasukTahunProd['success']) {
		return $updateDetailSaldo;
	}

	return ['success' => false, 'message' => "gagal update detail saldo"];
}
