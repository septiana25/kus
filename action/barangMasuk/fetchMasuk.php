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


	$sql = "SELECT rak.rak, brg, tgl, jam, jml_msk, id_det_msk, MONTH(tgl) AS bulan, YEAR(tgl) AS tahun, ket, suratJln, retur
			FROM detail_masuk
			JOIN masuk AS msk USING(id_msk)
			JOIN detail_brg USING(id)
			JOIN barang USING(id_brg)
			JOIN rak USING(id_rak)
			WHERE retur = '0' AND status_msk = '0' AND MONTH(tgl)=$bulan  AND YEAR(tgl)=$tahun 
			ORDER BY id_det_msk DESC";
	$result = $koneksi->query($sql);

	$output = array('data' => array());

	if ($result->num_rows > 0) {


	while ($row = $result->fetch_array()) {
	$id_det_msk = $row[5];
	$tgl = TanggalIndo($row['tgl']);
	//$tgl = tgl_indo($row[2]);
	if ($bulan == $row['bulan'] AND $tahun == $row['tahun']) {

		//$hapus = '<a href="#hapusModalMasuk" role="button" class="btn btn-small btn-danger" data-toggle="modal" onclick="hapusMasuk('.$id_det_msk.')"> <i class="icon-trash"></i>';
		$edit = '<li><a href="#editModalMasuk" onclick="editMasuk('.$id_det_msk.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>';

		$hapus = '<li><a href="#hapusModalMasuk" onclick="hapusMasuk('.$id_det_msk.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';


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

      if ($row['retur'] == 0)
      {
      	$ket = $row['ket'];
      }
      elseif($row['retur'] == 1)
      {
      	$ket = "Retur";
      }
      elseif($row['retur'] == 2)
      {
      	$ket = "Mutasi";
      }
      elseif($row['retur'] == 3)
      {
      	$ket = "Mutasi Rak";
      }

	$output['data'][] = array(
		$row[0],
		utf8_encode($row[1]),
		$row['suratJln'],
		$ket,
		$tgl,
		$row[3],
		$row[4],
		$button);
	}//while
	}//if
$koneksi->close();

echo json_encode($output);
?>