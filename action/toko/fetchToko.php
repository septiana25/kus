<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/toko.php';

$tokoClass = new Toko($koneksi);

try {
	$result = handleFetchToko($tokoClass);
	echo json_encode($result);
} catch (\Throwable $th) {
	error_log($th);
	echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
	$koneksi->close();
}

function generateButton($id_toko)
{
	return '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#editModalToko" onclick="editToko(' . $id_toko . ')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
        </ul>
    </div>';
}

function handleFetchToko($tokoClass)
{
	$result = $tokoClass->fetchAll();
	$output = array('data' => array());
	$no = 1;
	while ($row = $result->fetch_array()) {
		$button = generateButton($row['id_toko']);
		$output['data'][] = array(
			$no,
			$row['kode_toko'],
			$row['toko'],
			$row['alamat'],
			$button
		);
		$no++;
	}
	return $output;
}
