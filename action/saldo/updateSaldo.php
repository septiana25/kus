<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
	$tgl1  = date("Y-m-d", strtotime("-1 months"));
	$bulan = date("m", strtotime($tgl1));
	$tahun = date("Y", strtotime($tgl1));
	$tgl   = date("Y-m-d");
	$b     = date("m");
	// $bulan = 7;
	// $tahun = 2017;
	// $tgl   = date("Y-m-d");
	// $b     = 8;

	$query = $koneksi->query("SELECT id, saldo_akhir FROM saldo WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun AND saldo_akhir !=0");
	$sql_error = '';

	//membuat fungsi transaksi
	$koneksi->begin_transaction();

	if ($query->num_rows == 0) {
		$valid['success']  = "cek_tgl";
		$valid['messages'] = "<strong>Error! </strong> Tanggal Ngaco. Silahkan Hubungi Pihak IT";
	} else {


		$insert = "INSERT INTO saldo (id, tgl, saldo_awal, saldo_akhir) VALUES";
		// $insert = "INSERT INTO saldo (id, tgl, saldo_awal, saldo_akhir) VALUES ('$id', '$tgl', '$saldo_akhir', '$saldo_akhir')";
		$no = 1;
		while ($row = $query->fetch_array()) {
			$id[$no]          = $row[0];
			$saldo_akhir[$no] = $row[1];
			$insert           .= "('" . $id[$no] . "','" . $tgl . "','" . $saldo_akhir[$no] . "','" . $saldo_akhir[$no] . "'),";
			$no++;
		}
		$insert = rtrim($insert, ', ');
		// echo $insert;
		if ($koneksi->query($insert) === TRUE) {
			$valid['success']  = true;
			$valid['messages'] = "<strong>Success! </strong>Perpindahan Saldo Bulan " . $BulanIndo[(int)$b - 1] . " " . $tahun . "  Berhasil Disimpan";
		} else {

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan " . $koneksi->error;
			$sql_error .= 'error';
		}
	}

	if ($sql_error) {

		$koneksi->rollback(); //batal semua data simpan

	} else {

		$koneksi->commit(); //simpan semua data simpan

	}

	$koneksi->close();
	echo json_encode($valid);
}
