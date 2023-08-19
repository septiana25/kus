<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';

$query = "SELECT id_tmbh, no_toll, pemegang, tmbh_saldo, tgl_tmbh, stus_tmbh FROM tblTmbhSaldo
JOIN tblEToll USING(id_toll) WHERE tmbh_saldo != 0 ORDER BY id_tmbh DESC";

$result = $koneksi->query($query);

$output = array('data' => array());

if ($result->num_rows > 0) {
	$no =1;
	while ($row = $result->fetch_assoc()) {
		$id_tmbh = $row['id_tmbh'];

    if ($row['stus_tmbh'] == 1) {
    	$action = '<li><a><i class="fa fa-ban"></i> Sudah CLSD</a></li>';
    }else{

    	$action = '<li><a href="#editModalBarang" onclick="editBarang('.$id_tmbh.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
             <li><a href="#hapusModalBarang" onclick="hapusBarang('.$id_tmbh.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';
    }

	$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">
             '.$action.'
         </ul>
      </div>';

	if ($row['stus_tmbh'] == 0) {
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
      	format_rupiah($row['tmbh_saldo']),
      	TanggalIndo($row['tgl_tmbh']),
      	$status,
      	$button);
      $no++;

	}
}

$koneksi->close();

echo json_encode($output);

?>