<?php
/* fetchSelectedUkuran */
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/keluar.php';

header('Content-Type: application/json');

$keluarClass = new Keluar($koneksi);

try {
	if (!isset($_POST['nofak']) || empty($_POST['nofak'])) {
		throw new Exception('Nomor faktur tidak valid');
	}

	$nofak = filter_input(INPUT_POST, 'nofak', FILTER_SANITIZE_STRING);
	if (!$nofak) {
		throw new Exception('Nomor faktur tidak valid');
	}

	$result = $keluarClass->getByIdKlrJoinItem($nofak);
	if (!$result) {
		throw new Exception('Gagal mengambil data');
	}
	$tmpReturn = $keluarClass->fetchTmpReturn();
	if (!$tmpReturn) {
		throw new Exception('Gagal mengambil data');
	}

	$dataKeluar = [];
	while ($row = $result->fetch_assoc()) {
		$dataKeluar[] = [
			'id_det_klr' => $row['id_det_klr'],
			'id_brg' => $row['id_brg'],
			'brg' => $row['brg']
		];
	}


	$dataReturn = [];
	while ($row = $tmpReturn->fetch_assoc()) {
		$dataReturn[] = [
			'id_brg' => $row['id_brg'],
			'id_rak' => $row['id_rak'],
			'brg' => $row['brg'],
			'rak' => $row['rak'],
			'sisa' => $row['sisa']
		];
	}
	$mergedAndFilteredData = mergeAndFilterArrays($dataKeluar, $dataReturn);

	$output = ['data' => []];

	foreach ($mergedAndFilteredData as $row) {
		$output['data'][] = [
			'id' => $row['id_det_klr'],
			'id_rak' => $row['id_rak'],
			'rak' => $row['rak'],
			'nama' => htmlspecialchars($row['brg'], ENT_QUOTES, 'UTF-8'),
			'sisa' => $row['sisa']
		];
	}

	echo json_encode($output);
} catch (Exception $e) {
	http_response_code(400);
	echo json_encode(['error' => $e->getMessage()]);
} finally {
	$koneksi->close();
}

function mergeAndFilterArrays($dataKeluar, $dataReturn)
{
	$mergedData = [];

	foreach ($dataKeluar as $keluar) {
		$matchingReturns = array_filter($dataReturn, function ($return) use ($keluar) {
			return $return['id_brg'] == $keluar['id_brg'];
		});

		if (!empty($matchingReturns)) {
			foreach ($matchingReturns as $return) {
				$mergedData[] = array_merge($keluar, $return);
			}
		}
	}

	return $mergedData;
}
