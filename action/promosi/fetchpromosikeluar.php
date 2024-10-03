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

function generateButton($id_proklr)
{
	return '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#disableaccess" onclick="editEkpedisi(' . $id_proklr . ')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
            <li><a href="#printNota" onclick="printNota(' . $id_proklr . ')" data-toggle="modal"><i class="icon-print"></i> Print</a></li>
        </ul>
    </div>';
}

function handleFetchPromosi($ekspedisiClass)
{
	$result = $ekspedisiClass->fetchAllPromosiKeluar();
	$output = array('data' => array());

	while ($row = $result->fetch_array()) {
		$date = new DateTime($row['at_create']);
		$formattedDate = $date->format('d-m-Y');
		$button = generateButton($row['id_proklr']);
		$output['data'][] = array(
			$row['no_trank'],
			$row['divisi'],
			$row['sales'],
			$row['toko'],
			$row['item'],
			$row['qty'],
			$formattedDate,
			$button
		);
	}
	return $output;
}
