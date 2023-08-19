<?php

	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$sql = "SELECT msk.id_kat, msk.kat, IFNULL(total_msk, 0) - IFNULL(total_klr, 0) AS total_stok
			FROM( 
				SELECT id_kat, kat, SUM( IFNULL(jml_msk, 0)) AS total_msk
				FROM masuk
				LEFT JOIN detail_masuk USING(id_msk)
				LEFT JOIN detail_brg USING(id)
				LEFT JOIN barang USING(id_brg)
				RIGHT JOIN kat USING(id_kat)
				GROUP BY kat
			)msk
			LEFT JOIN(
				SELECT kat, SUM( IFNULL(jml_klr, 0)) AS total_klr
				FROM detail_keluar
			    LEFT JOIN keluar USING (id_klr)
			    LEFT JOIN detail_brg USING(id)
				LEFT JOIN barang USING(id_brg)
				RIGHT JOIN kat USING(id_kat)
				GROUP BY kat
			)klr ON  msk.kat=klr.kat
			";
	$result = $koneksi->query($sql);

	$output = array('data' => array());

	if ($result->num_rows > 0) {

	while ($row = $result->fetch_array()) {
	$id_kat = $row[0];
	//$button = '<a href="#editKategoriModal" role="button" class="btn btn-small btn-primary " id="editKategoriBtnModal" data-toggle="modal" onclick="editKategori('.$id_kat.')"> <i class="icon-pencil"></i>';
	$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">
             <li><a href="#editKategoriModal" onclick="editKategori('.$id_kat.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
             <li><a href="#hapusModalKategori" onclick="hapusKategori('.$id_kat.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
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