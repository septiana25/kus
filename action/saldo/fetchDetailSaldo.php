<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/detailsaldo.php';

$detailSaldoClass = new DetailSaldo($koneksi);
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if (empty($id)) {
	echo json_encode(['error' => 'No data found.']);
	exit;
}

try {
	$result = handleFetchDetailSaldo($detailSaldoClass, $id);
	echo json_encode($result);
} catch (\Throwable $th) {
	error_log($th);
	echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
	$koneksi->close();
}

function generateButton($id_detailsaldo)
{
	return '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#editModalBarang" onclick="editBarang(' . $id_detailsaldo . ')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
            <li><a href="#hapusModalBarang" onclick="hapusBarang(' . $id_detailsaldo . ')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
        </ul>
    </div>';
}

function handleFetchDetailSaldo($detailSaldoClass, $id)
{
	$result = $detailSaldoClass->getDetailSaldoByid($id);
	$output = array('data' => array());
	$data = array();
	while ($row = $result->fetch_array()) {
		$button = generateButton($row['id_detailsaldo']);
		$year = substr($row['tahunprod'], 2, 4);
		$week = substr($row['tahunprod'], 0, 2);
		$start = date("Y-m-d", strtotime("01 Jan 20" . $year . " 00:00:00 GMT + " . $week . " weeks"));

		$data[] = [
			$start,
			$row['tahunprod'],
			$row['jumlah'],
		];
	}
	usort($data, function ($a, $b) {
		return strtotime($a[0]) - strtotime($b[0]);
	});
	$output = array('data' => $data);

	return $output;
}
