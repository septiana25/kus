<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../../function/fungsi_rupiah.php';

if ($_POST) {
$id = $koneksi->real_escape_string($_POST['postNoEtoll']);
//$id = 4;
$query = "SELECT a1.id_toll, tmbh_saldo, rute, ruteAkhir, bayar
FROM(	
	SELECT id_toll, rute, ruteAkhir, bayar FROM  tblTransToll
	JOIN tblDetTransToll USING(id_trans)
	WHERE stus_trans = 0 AND bayar !=0 AND id_toll = $id
)a1
LEFT JOIN (
	SELECT id_toll, tmbh_saldo FROM tblTmbhSaldo
	WHERE stus = 0 AND tmbh_saldo !=0 AND id_toll = $id GROUP BY id_toll
)a2 ON a1.id_toll=a2.id_toll";

$result = $koneksi->query($query);
$fetch = $result->fetch_all(MYSQL_ASSOC);
foreach ($fetch as $key => $val) {
	$result1[$val['id_toll']][] = $val;
}


$output = array('data' => array());
$no=1;
foreach ($fetch as $key => $val) {
	
	if ($key==0) {
		$topup = $val['tmbh_saldo'];
	}else{
		$topup = '';
	}

	$output['data'][] = array(
		$no,
		format_rupiah($topup),
		$val['rute'],
		$val['ruteAkhir'],
		format_rupiah($val['bayar']));

$no++;
}
/*$no = 1;
if ($result->num_rows > 0) {
	//$row = $result->fetch_assoc();
	while ($row = $result->fetch_array()) {
		$output['data'][] = array(
			$no,
			$row['rute'],
			$row['ruteAkhir'],
			$row['bayar']);
		$no++;
	}
}*/



$koneksi->close();
echo json_encode($output);
}
?>