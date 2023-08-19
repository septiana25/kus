<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/setjam.php';
	require_once '../../function/session.php';

	$tahun          = date("Y");
	$bulan          = date("m");
	$sql = "SELECT b.id_brg AS id_brg, nourt, kdbrg, b.brg, d.rak, saldo_awal, saldo_akhir, kat
			FROM(
			SELECT id_brg, rak, saldo_awal, saldo_akhir, tgl
			FROM detail_brg
			LEFT JOIN rak USING(id_rak)
			LEFT JOIN saldo USING(id)
			WHERE MONTH(tgl)=$bulan  AND YEAR(tgl)=$tahun
			)d
			RIGHT JOIN (
			SELECT id_brg, kdbrg, brg, nourt, kat
			FROM barang
			JOIN kat USING(id_kat)
			)b ON b.id_brg=d.id_brg ORDER BY rak, b.brg ASC";


	$result = $koneksi->query($sql);

	$output = array('data' => array());

	if ($result->num_rows > 0) {

	while ($row = $result->fetch_array()) {
	$id_brg = $row[0];

	$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">
             <li><a href="#editModalBarang" onclick="editBarang('.$id_brg.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
             <li><a href="#hapusModalBarang" onclick="hapusBarang('.$id_brg.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
         </ul>
      </div>';
	   


	$output['data'][] = array(
		$row[1],
		$row[2],
		utf8_encode($row[3]),
		$row[4],
		$row[7],
		$row[5],
		$row[6],
		$button);
	}//while
	}//if
$koneksi->close();

echo json_encode($output);
?>