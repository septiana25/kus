<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/promosi.php';

$promosiClass = new Promosi($koneksi);

try {
	$result = handleFetchPromosi($promosiClass);
	echo json_encode($result);
} catch (\Throwable $th) {
	error_log($th);
	echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
	$koneksi->close();
}

function generateButton($id_promo)
{
	return '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#disableaccess" onclick="editEkpedisi(' . $id_promo . ')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
        </ul>
    </div>';
}

function handleFetchPromosi($ekspedisiClass)
{
	$result = $ekspedisiClass->fetchAll();
	$output = array('data' => array());

	while ($row = $result->fetch_array()) {
		$button = generateButton($row['id_promo']);
		$output['data'][] = array(
			$row['divisi'],
			$row['item'],
			$row['jenis'],
			$row['saldo'],
			$button
		);
	}
	return $output;
}
