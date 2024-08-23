<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';

header('Content-Type: application/json');

try {
	if (!isset($_POST['nofak']) || empty($_POST['nofak'])) {
		throw new Exception('Nomor faktur tidak valid');
	}

	$nofak = filter_input(INPUT_POST, 'nofak', FILTER_SANITIZE_STRING);
	if (!$nofak) {
		throw new Exception('Nomor faktur tidak valid');
	}

	$query = "SELECT dk.id_det_klr, b.brg 
              FROM keluar k
              JOIN detail_keluar dk ON k.id_klr = dk.id_klr
              JOIN detail_brg db ON dk.id = db.id
              JOIN barang b ON db.id_brg = b.id_brg
              WHERE k.id_klr = ?
              ORDER BY b.brg ASC";

	$stmt = $koneksi->prepare($query);
	if (!$stmt) {
		throw new Exception('Prepare statement gagal: ' . $koneksi->error);
	}

	$stmt->bind_param('s', $nofak);
	if (!$stmt->execute()) {
		throw new Exception('Execute statement gagal: ' . $stmt->error);
	}

	$result = $stmt->get_result();
	$output = ['data' => []];

	while ($row = $result->fetch_assoc()) {
		$output['data'][] = [
			'id' => $row['id_det_klr'],
			'nama' => htmlspecialchars($row['brg'], ENT_QUOTES, 'UTF-8')
		];
	}

	echo json_encode($output);
} catch (Exception $e) {
	http_response_code(400);
	echo json_encode(['error' => $e->getMessage()]);
} finally {
	if (isset($stmt)) {
		$stmt->close();
	}
	if (isset($koneksi)) {
		$koneksi->close();
	}
}
