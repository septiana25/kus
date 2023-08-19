<?php
include_once('../../function/koneksi.php');
include_once('../../function/session.php');

$Select = "SELECT b.id_brg, brg, btsLimit, total
FROM(
SELECT id_brg, brg, tgl, SUM(saldo_akhir) AS total FROM saldo
JOIN detail_brg USING(id)
JOIN barang USING(id_brg)
WHERE (MONTH(tgl)=8 AND YEAR(tgl)=2017)
GROUP BY id_brg
)b
LEFT JOIN(
SELECT id_brg, btsLimit FROM tblLimit
)c ON b.id_brg=c.id_brg WHERE ";

	$queryCekSaldo = "SELECT id_brg, btsLimit FROM tblLimit ORDER BY id_brg ASC";
	$resCek = $koneksi->query($queryCekSaldo);
	while ($row = $resCek->fetch_array()) {
		$id_brg= $row[0];
		$limit=$row[1];
		// echo $id." ".$tgl."<br/>";
		$Select .= "( b.id_brg = " .$id_brg." AND "."total < ".$limit." ) OR ";
	}

	$Select = rtrim($Select, 'OR ');
	// echo $Select;
	
	$resSelect = $koneksi->query($Select);

	$output = array('data' => array());

	if ($resSelect->num_rows > 0) {
		$no=1;
		while ($rowLimit = $resSelect->fetch_array()) {

			$id1 = $rowLimit[0];
			$button = '<a href="#editModalBarang" onclick="editBarang('.$id1.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a>';

			$output['data'][] = array(
				$no,
				$rowLimit[1],
				$rowLimit[2],
				$rowLimit[3],
				$button);
			$no++;
		}//while
	}//if

	$koneksi->close();

	echo json_encode($output);

?>