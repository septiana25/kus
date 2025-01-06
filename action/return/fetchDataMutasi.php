<?php
/* fetchDataRetur */

require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../class/mutasi.php';

$classMutasi = new Mutasi($koneksi);


try {
	$result = $classMutasi->fetchAll();
	$output = array('data' => array());

	while ($row = $result->fetch_array()) {
		$button = generateButton($row['id_mutasi']);
		$rak = generateRak($row['rak_asal'], $row['rak_tujuan']);

		$output['data'][] = array(
			$row['brg'],
			$rak,
			$row['tahunprod'],
			$row['qty'],
			$button
		);
	}
	echo json_encode($output);
} catch (Exception $e) {
	echo json_encode(array('error' => $e->getMessage()));
} finally {
	$koneksi->close();
}

function generateButton($id_mutasi)
{
	return '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#modalApproved" onclick="reject(' . $id_mutasi . ')" data-toggle="modal"><i class="icon-pencil"></i> Batal</a></li>            
        </ul>
    </div>';
}

function generateRak($rak_asal, $rak_tujuan)
{
	return '<span class="label label-info">' . $rak_asal . '</span> <i class="icon-arrow-right"></i> <span class="label label-success">' . $rak_tujuan . '</span>';
}
