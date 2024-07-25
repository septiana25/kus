<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/ekspedisi.php';

$ekspedisiClass = new Ekspedisi($koneksi);

try {
	$result = handleFetchDetailSaldo($ekspedisiClass);
	echo json_encode($result);
} catch (\Throwable $th) {
	error_log($th);
	echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
	$koneksi->close();
}

function generateButton($id_eks)
{
	return '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#disableaccess" onclick="editEkpedisi(' . $id_eks . ')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
            <li><a href="#disableaccess" onclick="deleteEkspedisi(' . $id_eks . ')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
        </ul>
    </div>';
}

function handleFetchDetailSaldo($ekspedisiClass)
{
	$result = $ekspedisiClass->fetchAll();
	$output = array('data' => array());

	while ($row = $result->fetch_array()) {
		$button = generateButton($row['id_eks']);
		$output['data'][] = array(
			$row['nopol'],
			$row['supir'],
			$row['jenis'],
			$button
		);
	}
	return $output;
}
