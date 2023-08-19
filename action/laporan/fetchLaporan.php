<?php

	require_once '../../function/koneksi.php';
	require_once '../../function/tgl_indo.php';
	require_once '../../function/session.php';

	$tgl          = date("Y-m-d");
	$jam          = date("H:i:s");
	$bulan        = date("m");
	$tahun        = date("Y");

	$sql = "SELECT k.rak, k.brg,
	IFNULL(saldo_awal, 0) AS s_awal,
	IFNULL(total_masuk,0) AS b_masuk,
	IFNULL(tgl_1, 0) AS tgl_1, IFNULL(tgl_2, 0) AS tgl_2,
	IFNULL(tgl_3, 0) AS tgl_3, IFNULL(tgl_4, 0) AS tgl_4,
	IFNULL(tgl_5, 0) AS tgl_5, IFNULL(tgl_6, 0) AS tgl_6,
	IFNULL(tgl_7, 0) AS tgl_7, IFNULL(tgl_8, 0) AS tgl_8,
	IFNULL(tgl_9, 0) AS tgl_9, IFNULL(tgl_10, 0) AS tgl_10,
	IFNULL(tgl_11, 0) AS tgl_11, IFNULL(tgl_12, 0) AS tgl_12,
	IFNULL(tgl_13, 0) AS tgl_13, IFNULL(tgl_14, 0) AS tgl_14,
	IFNULL(tgl_15, 0) AS tgl_15, IFNULL(tgl_16, 0) AS tgl_16,
	IFNULL(tgl_17, 0) AS tgl_17, IFNULL(tgl_17, 0) AS tgl_17,
	IFNULL(tgl_18, 0) AS tgl_18, IFNULL(tgl_19, 0) AS tgl_19,
	IFNULL(tgl_20, 0) AS tgl_20, IFNULL(tgl_21, 0) AS tgl_21,
	IFNULL(tgl_22, 0) AS tgl_22, IFNULL(tgl_23, 0) AS tgl_23,
	IFNULL(tgl_24, 0) AS tgl_24, IFNULL(tgl_25, 0) AS tgl_25,
	IFNULL(tgl_26, 0) AS tgl_26, IFNULL(tgl_27, 0) AS tgl_27,
	IFNULL(tgl_28, 0) AS tgl_28, IFNULL(tgl_29, 0) AS tgl_29,
	IFNULL(tgl_30, 0) AS tgl_30, IFNULL(tgl_30, 0) AS tgl_31,
	IFNULL(total_keluar,0) AS total_keluar,
	IFNULL(saldo_akhir, 0) AS s_akhir
FROM(
	SELECT rak, brg, tgl, SUM(jml_klr) AS total_keluar,
		SUM( IF( DAY(tgl)=1, jml_klr, 0)) AS tgl_1,
		SUM( IF( DAY(tgl)=2, jml_klr, 0)) AS tgl_2,
		SUM( IF( DAY(tgl)=3, jml_klr, 0)) AS tgl_3,
		SUM( IF( DAY(tgl)=4, jml_klr, 0)) AS tgl_4,
		SUM( IF( DAY(tgl)=5, jml_klr, 0)) AS tgl_5,
		SUM( IF( DAY(tgl)=6, jml_klr, 0)) AS tgl_6,
		SUM( IF( DAY(tgl)=7, jml_klr, 0)) AS tgl_7,
		SUM( IF( DAY(tgl)=8, jml_klr, 0)) AS tgl_8,
		SUM( IF( DAY(tgl)=9, jml_klr, 0)) AS tgl_9,
		SUM( IF( DAY(tgl)=10, jml_klr, 0)) AS tgl_10,
		SUM( IF( DAY(tgl)=11, jml_klr, 0)) AS tgl_11,
		SUM( IF( DAY(tgl)=12, jml_klr, 0)) AS tgl_12,
		SUM( IF( DAY(tgl)=13, jml_klr, 0)) AS tgl_13,
		SUM( IF( DAY(tgl)=14, jml_klr, 0)) AS tgl_14,
		SUM( IF( DAY(tgl)=15, jml_klr, 0)) AS tgl_15,
		SUM( IF( DAY(tgl)=16, jml_klr, 0)) AS tgl_16,
		SUM( IF( DAY(tgl)=17, jml_klr, 0)) AS tgl_17,
		SUM( IF( DAY(tgl)=18, jml_klr, 0)) AS tgl_18,
		SUM( IF( DAY(tgl)=19, jml_klr, 0)) AS tgl_19,
		SUM( IF( DAY(tgl)=20, jml_klr, 0)) AS tgl_20,
		SUM( IF( DAY(tgl)=21, jml_klr, 0)) AS tgl_21,
		SUM( IF( DAY(tgl)=22, jml_klr, 0)) AS tgl_22,
		SUM( IF( DAY(tgl)=23, jml_klr, 0)) AS tgl_23,
		SUM( IF( DAY(tgl)=24, jml_klr, 0)) AS tgl_24,
		SUM( IF( DAY(tgl)=25, jml_klr, 0)) AS tgl_25,
		SUM( IF( DAY(tgl)=26, jml_klr, 0)) AS tgl_26,
		SUM( IF( DAY(tgl)=27, jml_klr, 0)) AS tgl_27,
		SUM( IF( DAY(tgl)=28, jml_klr, 0)) AS tgl_28,
		SUM( IF( DAY(tgl)=29, jml_klr, 0)) AS tgl_29,
		SUM( IF( DAY(tgl)=30, jml_klr, 0)) AS tgl_30,
		SUM( IF( DAY(tgl)=31, jml_klr, 0)) AS tgl_31
	FROM `detail_brg`
	LEFT JOIN keluar USING (id)
	LEFT JOIN detail_keluar USING(id_klr)
	RIGHT JOIN barang USING(id_brg)
	LEFT JOIN rak USING(id_rak)
	WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun 
	GROUP BY rak, brg
)k
LEFT JOIN(
	SELECT rak, brg, tgl, SUM( IFNULL(jml_msk, 0)) AS total_masuk
	FROM `detail_brg`
	LEFT JOIN masuk USING(id)
	LEFT JOIN detail_masuk USING(id_msk)
	RIGHT JOIN barang USING(id_brg)
	LEFT JOIN rak USING(id_rak)
	WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun 
	GROUP BY rak, brg
)m ON k.brg=m.brg AND k.rak=m.rak
LEFT JOIN (
	SELECT rak, brg, saldo_awal, saldo_akhir
	FROM `detail_brg`
	JOIN saldo ON `detail_brg`.`id`=saldo.`id`
	JOIN barang ON `detail_brg`.`id_brg`=barang.`id_brg`
	JOIN rak ON detail_brg.`id_rak`=rak.`id_rak`
	WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun 
	GROUP BY rak, brg
)s ON k.brg=s.brg AND k.rak=s.rak
-- RIGHT JOIN(
-- 	SELECT rak, brg
-- 	FROM detail_brg
-- 	RIGHT JOIN barang USING(id_brg)
-- 	LEFT JOIN rak USING(id_rak)
-- 	GROUP BY rak, brg
-- )b ON k.brg=b.brg AND k.rak=b.rak
";

	$result = $koneksi->query($sql);

	$output = array('data' => array());

	if ($result->num_rows > 0) {

	while ($row = $result->fetch_array()) {
	//$categoriesId = $row[0];
	//$tgl = TanggalIndo($row['tgl']);
	//$tgl = tgl_indo($row[2]);
	// $button = '<!-- Single button -->
	// <div class="btn-group">
	//   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	//     Action <span class="caret"></span>
	//   </button>
	//   <ul class="dropdown-menu">
	//     <li><a type="button" data-toggle="modal" id="editCategoriesModalBtn" data-target="#editCategoriesModal" onclick="editCategories('.$categoriesId.')"> <i class="glyphicon glyphicon-edit"></i> Edit</a></li>
	//     <li><a type="button" data-toggle="modal" data-target="#removeCategoriesModal" id="removeCategoriesModalBtn" onclick="removeCategories('.$categoriesId.')"> <i class="glyphicon glyphicon-trash"></i> Remove</a></li>       
	//   </ul>
	// </div>';


	$output['data'][] = array(
		$row['rak'],
		$row['brg'],
		$row['s_awal'],
		$row['b_masuk'],
		$row['tgl_1'],
		$row['tgl_2'],
		$row['tgl_3'],
		$row['tgl_4'],
		$row['tgl_5'],
		$row['tgl_6'],
		$row['tgl_7'],
		$row['tgl_8'],
		$row['tgl_9'],
		$row['tgl_10'],
		$row['tgl_11'],
		$row['tgl_12'],
		$row['tgl_13'],
		$row['tgl_14'],
		$row['tgl_15'],
		$row['tgl_16'],
		$row['tgl_17'],
		$row['tgl_18'],
		$row['tgl_19'],
		$row['tgl_20'],
		$row['tgl_21'],
		$row['tgl_22'],
		$row['tgl_23'],
		$row['tgl_24'],
		$row['tgl_25'],
		$row['tgl_26'],
		$row['tgl_27'],
		$row['tgl_28'],
		$row['tgl_29'],
		$row['tgl_30'],
		$row['tgl_31'],
		$row['total_keluar'],
		$row['s_akhir']);
	}//while
	}//if
$koneksi->close();
// echo "<pre>";
// print_r($output);
// echo "</pre>";
echo json_encode($output);
?>