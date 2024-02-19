<?php

require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';
require_once '../class/keluar.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) //jika  id detail masuk ada
{
	$detailSaldoClass = new DetailSaldo($koneksi);
	$keluarClass = new Keluar($koneksi);

	$id_det_klr  = $koneksi->real_escape_string($_POST['id_det_klr']);

	$cek_brg     = $koneksi->query("SELECT id_klr, id, jml_klr FROM detail_keluar JOIN keluar USING(id_klr) WHERE id_det_klr=$id_det_klr");
	$rowBrg = $cek_brg->fetch_array();
	$id     = $rowBrg['id'];
	$id_klr = $rowBrg['id_klr'];
	$jml    = $rowBrg['jml_klr'];

	$cek_klr = $koneksi->query("SELECT id_klr, id, jml_klr FROM detail_keluar JOIN keluar USING(id_klr) WHERE id_klr=$id_klr");

	// if ($cek_klr->num_rows == 1) {
	// 	$keluar = $koneksi->query("DELETE FROM keluar WHERE id_klr=$id_klr");
	// }

	$cek_saldo   = $koneksi->query("SELECT id_saldo, saldo_akhir FROM saldo WHERE id=$id ORDER BY id_saldo DESC LIMIT 0,1");
	$rowSaldo    = $cek_saldo->fetch_array();

	$id_saldo    = $rowSaldo['id_saldo'];
	$total       = $rowSaldo['saldo_akhir'];
	$total_akhir = $total + $rowBrg['jml_klr'];

	$sql_success = "";

	//membuat fungsi transaksi
	$koneksi->begin_transaction();

	if ($cek_klr->num_rows == 1) //cek jika tabel keluar dan detail keluar ada satu
	{

		$detKlr = "DELETE FROM keluar WHERE id_klr=$id_klr";

		if ($koneksi->query($detKlr) === TRUE) //cek jika data table keluar berhasil di hapus
		{

			$update = "UPDATE saldo SET saldo_akhir=$total_akhir WHERE id_saldo=$id_saldo";

			$idDetailSaldo =  handleIdDetailSaldo($keluarClass, $detailSaldoClass, $id_det_klr, $id);
			$updateDetailSaldo = handleUpdateDetailSaldo($detailSaldoClass, $keluarClass, $idDetailSaldo, $id_det_klr, $jml);

			if ($koneksi->query($update) === TRUE && $updateDetailSaldo['success']) {

				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong>Data Berhasil Dihapus";

				$sql_success .= "success";
			} else {

				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong>Data Gagal Dihapus. Tabel Saldo(update) Error-AIG-0D09 " . $koneksi->error;
			}
		} else //cek jika data table keluar gagal di hapus
		{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong>Data Gagal Dihapus. Table Keluar Error-AIG-0D10 " . $koneksi->error;
		}
	} else //cek jika tabel keluar dan detail keluar lebih dari satu
	{

		$DetDelKlr = "DELETE FROM detail_keluar WHERE id_det_klr=$id_det_klr";

		if ($koneksi->query($DetDelKlr) === TRUE) {

			$update = "UPDATE saldo SET saldo_akhir=$total_akhir WHERE id_saldo=$id_saldo";

			$idDetailSaldo =  handleIdDetailSaldo($keluarClass, $detailSaldoClass, $id_det_klr, $id);

			$updateDetailSaldo = handleUpdateDetailSaldo($detailSaldoClass, $keluarClass, $idDetailSaldo, $id_det_klr, $jml);

			if ($koneksi->query($update) === TRUE && $updateDetailSaldo['success']) {

				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong>Data Berhasil Dihapus";

				$sql_success .= "success";
			} else {

				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong>Data Gagal Dihapus. Table Saldo(update) Error-AIG-0D11 " . $koneksi->error;
			}
		} else {

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong>Data Gagal Dihapus. Tabel Detail Keluar Error-AIG-0D12 " . $koneksi->error;
		}
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

function handleIdDetailSaldo($keluarClass, $detailSaldoClass, $idDetKeluar, $id)
{
	global $valid;
	$checkTahunProd = $keluarClass->getDetailKeluarTahunProd($idDetKeluar);
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
	$idDetailSaldo = $resultDetailSaldo['id_detailsaldo'];

	return $idDetailSaldo;
}

function handleUpdateDetailSaldo($detailSaldoClass, $keluarClass, $idDetailSaldo, $idDetKeluar, $jml)
{
	global $valid;

	try {
		$checkDetailSaldo = $detailSaldoClass->getDetailSaldoByidDetailsaldo($idDetailSaldo);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diambil. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	$resultDetailSaldo = $checkDetailSaldo->fetch_array();
	$idDetailSaldo = $resultDetailSaldo['id_detailsaldo'];
	$totalJumlah = $resultDetailSaldo['jumlah'] + $jml;
	$updateDetailSaldo = $detailSaldoClass->update($idDetailSaldo, $totalJumlah);
	$deleteDetailKeluarTahunProd = $keluarClass->deleteDetailKeluarTahunProd($idDetKeluar);

	if ($updateDetailSaldo['success'] && $deleteDetailKeluarTahunProd['success']) {
		return $updateDetailSaldo;
	}
}
