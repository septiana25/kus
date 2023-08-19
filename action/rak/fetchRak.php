<?php

	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';
	//require_once '../../function/tgl_indo.php';
	$tahun          = date("Y");
	$bulan          = date("m");
	$sql = "SELECT id_rak, rak, SUM(saldo_akhir) AS total_stok
			FROM detail_brg
			LEFT JOIN rak USING(id_rak)
			LEFT JOIN saldo USING(id)
			WHERE MONTH(tgl)=$bulan  AND YEAR(tgl)=$tahun
			GROUP BY id_rak";

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