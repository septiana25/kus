<?php

	require_once '../../function/koneksi.php';
	require_once '../../function/tgl_indo.php';
	require_once '../../function/session.php';

	$sql = "SELECT rak.rak, brg, tgl, jam, jml_msk, id_det_msk, MONTH(tgl) AS bulan, YEAR(tgl) AS tahun, ket, suratJln, retur,
			no_faktur, SUBSTR(no_faktur, 1,4) AS lama
			FROM detail_masuk
			LEFT JOIN masuk AS msk USING(id_msk)
			LEFT JOIN detail_brg USING(id)
			LEFT JOIN barang USING(id_brg)
			LEFT JOIN rak USING(id_rak)
			WHERE retur = '1'
			ORDER BY id_msk DESC";
	$result = $koneksi->query($sql);

	$output = array('data' => array());

	if ($result->num_rows > 0) {

	$bulan = date("m");
	$tahun = date("Y");

	while ($row = $result->fetch_array()) {
	$id_det_msk = $row[5];
	$tgl = TanggalIndo($row['tgl']);
	//$tgl = tgl_indo($row[2]);
	if ($bulan == $row['bulan'] AND $tahun == $row['tahun']) {

		//$hapus = '<a href="#hapusModalMasuk" role="button" class="btn btn-small btn-danger" data-toggle="modal" onclick="hapusMasuk('.$id_det_msk.')"> <i class="icon-trash"></i>';
		$edit = '<li><a href="#editModalRetur" onclick="editRetur('.$id_det_msk.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>';

		if ($row['lama']=='LAMA') {
			
			$hapus = '<li><a href="#hapusModalMasuk" onclick="hapusReturAlter('.$id_det_msk.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';

		}
		else
		{

			$hapus = '<li><a href="#hapusModalMasuk" onclick="hapusRetur('.$id_det_msk.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';
		}


	}else{

		$edit = '<li><a href="#hapusModalMasuk" onclick="hapusRetur()" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>';

		$hapus = '<li><a href="#hapusModalMasuk" onclick="hapusRetur()" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';

	}

	$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">

             '.$edit.$hapus.'
         </ul>
      </div>';



	$output['data'][] = array(
		$row[0],
		utf8_encode($row[1]),
		$row['no_faktur'],
		$row['suratJln'],
		$row[8],
		$tgl,
		$row[3],
		$row[4],
		$button);
	}//while
	}//if
$koneksi->close();

echo json_encode($output);
?>