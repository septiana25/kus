<?php

require_once '../../function/koneksi.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/session.php';

$b = date("m");
$t = date("Y");

$cariTglSaldo = $koneksi->query("SELECT MONTH(tgl) AS bulan, YEAR(tgl) AS tahun FROM saldo WHERE MONTH(tgl) = $b AND YEAR(tgl) = $t LIMIT 0,1");

if ($cariTglSaldo->num_rows == 1) {

	$bulan = $b;
	$tahun = $t;
} else {

	$caritglLama = $koneksi->query("SELECT MONTH(tgl) AS bulan, YEAR(tgl) AS tahun FROM saldo ORDER BY tgl DESC LIMIT 0,1");
	$rowtgl = $caritglLama->fetch_assoc();
	$bulan  = $rowtgl['bulan'];
	$tahun  = $rowtgl['tahun'];
}

$sql = "SELECT no_faktur, toko, rak, brg, tgl, jam, jml_klr, id_det_klr, pengirim, MONTH(tgl) AS bulan, YEAR(tgl) AS tahun
			FROM keluar
			RIGHT JOIN detail_keluar USING(id_klr)
			LEFT JOIN detail_brg USING(id)
			LEFT JOIN barang USING(id_brg)
			LEFT JOIN rak USING(id_rak)
			LEFT JOIN toko USING(id_toko)
			WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun AND status_klr='0'
			ORDER BY id_det_klr DESC";
$result = $koneksi->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {

	while ($row = $result->fetch_array()) {
		$id_det_klr = $row['id_det_klr'];
		$tgl = TanggalIndo($row['tgl']);
		// $button = '<a href="#hapusModalKeluar" role="button" class="btn btn-small btn-danger" id="hapusKeluarBtnModal" data-toggle="modal" onclick="hapusKeluar('.$id_det_klr.')"> <i class="icon-trash"></i>';

		if ($bulan == $row['bulan'] and $tahun == $row['tahun']) {

			//$hapus = '<a href="#hapusModalMasuk" role="button" class="btn btn-small btn-danger" data-toggle="modal" onclick="hapusMasuk('.$id_det_msk.')"> <i class="icon-trash"></i>';
			$edit = '<li><a href="#editModalKeluar" id="editKeluar1" onclick="editKeluar(' . $id_det_klr . ')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>';

			$hapus = '<li><a href="#hapusModalKeluar" onclick="hapusKeluar(' . $id_det_klr . ')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';
		} else {

			$edit = '<li><a href="#hapusModalKeluar" onclick="hapusKeluar()" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>';

			$hapus = '<li><a href="#hapusModalKeluar" onclick="hapusKeluar()" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';
		}

		$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">

             ' . $edit . $hapus . '
         </ul>
      </div>';

		$output['data'][] = array(
			$row[0],
			$row[1],
			$row[2],
			$row[3],
			$tgl,
			$row[5],
			$row[6],
			$row[8],
			$button
		);
	} //while
} //if
$koneksi->close();

echo json_encode($output);
