<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/saldo.php';

$saldoClass = new Saldo($koneksi);
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if (empty($id)) {
	echo json_encode(['error' => 'No data found.']);
	exit;
}
try {
	$checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
	$month = SUBSTR($checkSaldoLastDate, 5, -3);
	$year = SUBSTR($checkSaldoLastDate, 0, -6);

	$result = handleFetchSaldo($saldoClass, $id, $month, $year);
	echo json_encode($result);
} catch (\Throwable $th) {
	error_log($th);
	echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
	$koneksi->close();
}

function handleFetchSaldo($saldoClass, $id, $month, $year)
{
	$result = $saldoClass->getSaldoByidJoinDetail($id, $month, $year);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$output = array('data' => $row);
	} else {
		$output = ['error' => 'No data found.'];
	}

	return $output;
}
