<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/dataretur.php';

$classRetur = new DataRetur($koneksi);

$id_retur = isset($_POST['id_retur']) ? $_POST['id_retur'] : 0;

if ($id_retur == 0) {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleFetchKoreksiSaldoById($classRetur, $id_retur);
    echo json_encode($result);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}

function handleFetchKoreksiSaldoById($classRetur, $id_retur)
{

    $result = $classRetur->fetchReturById($id_retur);
    $row = $result->fetch_assoc();

    return $row;
}
