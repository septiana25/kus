<?php
/* fetchSelectedUkuran */
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/promosi.php';

header('Content-Type: application/json');

$promosiClass = new Promosi($koneksi);

try {
	if (!isset($_POST['divisi']) || empty($_POST['divisi'])) {
		throw new Exception('Divisi tidak valid');
	}

	$result = $promosiClass->getPromosiByDivisi($_POST['divisi']);
	if (!$result) {
		throw new Exception('Gagal mengambil data');
	}

	$output = ['data' => []];

	while ($row = $result->fetch_assoc()) {
		$output['data'][] = [
			'id' => $row['id_promo'],
			'item' => $row['item'],
			'saldo' => $row['saldo']
		];
	}

	echo json_encode($output);
} catch (Exception $e) {
	http_response_code(400);
	echo json_encode(['error' => $e->getMessage()]);
} finally {
	$koneksi->close();
}
