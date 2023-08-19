<?php

	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';
	//require_once '../../function/tgl_indo.php';

	$sql = "SELECT msk.id_rak, msk.rak, IFNULL(total_msk, 0) - IFNULL(total_klr, 0) AS total_stok
			FROM( 
				SELECT id_rak, rak.rak AS rak, SUM( IFNULL(jml_msk, 0)) AS total_msk
				FROM masuk
				LEFT JOIN detail_masuk USING(id_msk)
				LEFT JOIN detail_brg USING(id)
				RIGHT JOIN rak USING(id_rak)
				WHERE retur IN('0','1')
				GROUP BY rak
			)msk
			LEFT JOIN(
				SELECT rak, SUM( IFNULL(jml_klr, 0)) AS total_klr
				FROM detail_keluar
			    LEFT JOIN keluar USING (id_klr)
			    LEFT JOIN detail_brg USING(id)
				RIGHT JOIN rak USING(id_rak)
				GROUP BY rak
			)klr ON  msk.rak=klr.rak
			GROUP BY rak";

	$result = $koneksi->query($sql);

	$output = array('data' => array());

	if ($result->num_rows > 0) {

	while ($row = $result->fetch_array()) {
	$id_rak = $row[0];
	//$tgl = TanggalIndo($row['tgl']);
	//$tgl = tgl_indo($row[2]);
	//$button = '<a href="#editMoadlRak" role="button" class="btn btn-small btn-primary" id="editRakBtnModal" data-toggle="modal" onclick="editRakId('.$id_rak.')"> <i class="icon-pencil"></i>';
	$button = '<div class="btn-group">
	             <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
	             <ul class="dropdown-menu">
	                 <li><a href="#editMoadlRak" onclick="editRak('.$id_rak.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
	                 <li><a href="#hapusModalRak" onclick="hapusRak('.$id_rak.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
	             </ul>
	          </div>';


	$output['data'][] = array(
		$row[1],
		$row[2],
		$button);
	}//while
	}//if
$koneksi->close();

echo json_encode($output);
?>