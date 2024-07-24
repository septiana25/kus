<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';

$soClass = new Salesorder($koneksi);

$id_so = isset($_POST['id_so']) ? $_POST['id_so'] : 0;

if ($id_so == 0) {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleFetchKoreksiSaldoById($soClass, $id_so);
    echo json_encode($result);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}

function handleFetchKoreksiSaldoById($soClass, $id_so)
{

    $result = $soClass->fetchSelesOrderByid($id_so);
    $row = $result->fetch_assoc();

    return $row;
}
