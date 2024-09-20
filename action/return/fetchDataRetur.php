<?php
/* fetchDataRetur */

require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../class/dataretur.php';

$classRetur = new DataRetur($koneksi);


try {
	$result = $classRetur->fetchAll();
	$output = array('data' => array());

	while ($row = $result->fetch_array()) {
		$button = generateButton($row['id_retur']);

		$output['data'][] = array(
			$row['brg'],
			$row['rak'],
			$row['sisa'],
			$button
		);
	}
	echo json_encode($output);
} catch (Exception $e) {
	echo json_encode(array('error' => $e->getMessage()));
} finally {
	$koneksi->close();
}

function generateButton($id_retur)
{
	return '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#modalApproved" onclick="approved(' . $id_retur . ')" data-toggle="modal"><i class="icon-pencil"></i> Close</a></li>            
        </ul>
    </div>';
}
