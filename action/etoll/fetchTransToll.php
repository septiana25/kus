<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../../function/fungsi_rupiah.php';
require_once '../../function/tgl_indo.php';

$query = "SELECT id_DetTrans, no_toll, pemegang, rute, ruteAkhir, bayar, ket, tgl_trans, stus_trans
	FROM tblDetTransToll
	JOIN tblTransToll USING(id_trans) 
	JOIN tblEToll USING(id_toll) WHERE bayar != 0 ORDER BY id_DetTrans DESC";
$result = $koneksi->query($query);

$output = array('data' => array());

if ($result->num_rows > 0) {
	$no =1;
	while ($row = $result->fetch_array()) {

	$id_DetTrans = $row['id_DetTrans'];
	if ($row['ruteAkhir'] == '-' || empty($row['ruteAkhir'])) {
		$rute = $row['rute'];
	}elseif ($row['rute'] == '-' || empty($row['rute'])) {
		$rute = $row['ruteAkhir'];
	}
	else{
		$rute = $row['rute'].' - '.$row['ruteAkhir'];
	}

    if ($row['stus_trans'] == 1) {
    	$action = '<li><a><i class="fa fa-ban"></i> Sudah CLSD</a></li>';
    }else{

    	$action = '<li><a href="#editModalTransToll" onclick="editTransToll('.$id_DetTrans.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
    	<li><a href="#hapusModalBarang" onclick="hapusBarang('.$id_DetTrans.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';
    }

	$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">
             
            '.$action.'
             
         </ul>
      </div>';

		if ($row['stus_trans'] == 0) {
			$status = '<span class="label label-success">OPEN</span>';
		}/*elseif ($row['status'] == "Ganti SC"){
			$status = '<span class="label label-info">Ganti SC</span>';
		}*/
		else{
			$status = '<span class="label label-warning">CLSD</span>';
		}

		$output['data'][] = array(
			$no,
			$row['no_toll'],
			$row['pemegang'],
			$rute,
			format_rupiah($row['bayar']),
			$row['ket'],
			TanggalIndo($row['tgl_trans']),
			$status,
			$button);
		$no++;
	}
}

$koneksi->close();
echo json_encode($output);
?>