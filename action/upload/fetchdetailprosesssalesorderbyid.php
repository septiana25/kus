<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';

$soClass = new Salesorder($koneksi);

$id_pro = isset($_POST['id_pro']) ? $_POST['id_pro'] : 0;

if ($id_pro == 0) {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleFetchKoreksiSaldoById($soClass, $id_pro);
    echo json_encode($result);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}

function handleFetchKoreksiSaldoById($soClass, $id_pro)
{
    $result = $soClass->getDataDetailProsessSalesOrderByIdPro($id_pro);
    $row = $result->fetch_assoc();

    return $row;
}
