<?php

	require_once '../../function/koneksi.php';
	require_once '../../function/tgl_indo.php';
	require_once '../../function/session.php';

	$b = date("m");
	$t = date("Y");

	$cariTglSaldo = $koneksi->query("SELECT MONTH(tgl) AS bulan, YEAR(tgl) AS tahun FROM saldo WHERE MONTH(tgl) = $b AND YEAR(tgl) = $t LIMIT 0,1");

	if ($cariTglSaldo->num_rows == 1) 
	{

		$bulan = $b;
		$tahun = $t;
		
	}
	else
	{

		$caritglLama = $koneksi->query("SELECT MONTH(tgl) AS bulan, YEAR(tgl) AS tahun FROM saldo ORDER BY tgl DESC LIMIT 0,1");
		$rowtgl = $caritglLama->fetch_assoc();  
		$bulan  = $rowtgl['bulan'];
		$tahun  = $rowtgl['tahun'];

	}

	$sql = "SELECT suratJln, rak.rak AS rak, brg, tgl, jam, jml_msk, id_det_msk, ket, MONTH(tgl) AS bulan, YEAR(tgl) AS tahun
			FROM masuk
			RIGHT JOIN detail_masuk USING(id_msk)
			LEFT JOIN detail_brg USING(id)
			LEFT JOIN barang USING(id_brg)
			LEFT JOIN rak USING(id_rak)
			WHERE status_msk='1'
			ORDER BY id_det_msk DESC";
	$result = $koneksi->query($sql);

	$output = array('data' => array());

	if ($result->num_rows > 0) {

	while ($row = $result->fetch_array()) {
	$id_det_msk = $row['id_det_msk'];
	$tgl = TanggalIndo($row['tgl']);
	// $button = '<a href="#hapusModalKeluar" role="button" class="btn btn-small btn-danger" id="hapusKeluarBtnModal" data-toggle="modal" onclick="hapusKeluar('.$id_det_msk.')"> <i class="icon-trash"></i>';

	if ($bulan == $row['bulan'] AND $tahun == $row['tahun']) {

		//$hapus = '<a href="#hapusModalMasuk" role="button" class="btn btn-small btn-danger" data-toggle="modal" onclick="hapusMasuk('.$id_det_msk.')"> <i class="icon-trash"></i>';
		$edit = '<li><a href="#editModalPlus" id="editPlus" onclick="editPlus('.$id_det_msk.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>';

		$hapus = '<li><a href="#hapusModalKoreksi" onclick="hapusPlus('.$id_det_msk.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';

	}else{

		$edit = '<li><a href="#hapusModalMasuk" onclick="hapusMasuk()" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>';

		$hapus = '<li><a href="#hapusModalMasuk" onclick="hapusMasuk()" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';

	}

	$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">

             '.$edit.$hapus.'
         </ul>
      </div>';

	$output['data'][] = array(
		$row['rak'],
		$row['brg'],
		$row['suratJln'],
		$row['ket'],
		$tgl,
		$row['jam'],
		$row['jml_msk'],
		$button);
	}//while
	}//if
$koneksi->close();

echo json_encode($output);
?>