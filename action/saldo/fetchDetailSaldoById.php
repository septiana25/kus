<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';

$detailSaldoClass = new DetailSaldo($koneksi);
$idDetailSaldo = isset($_POST['idDetail']) ? $_POST['idDetail'] : 0;

if ($idDetailSaldo == 0) {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleFetchDetailSaldo($detailSaldoClass, $idDetailSaldo);
    echo json_encode($result);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}

function handleFetchDetailSaldo($detailSaldoClass, $idDetailSaldo)
{
    $result = $detailSaldoClass->getDetailSaldoByidDetailsaldo($idDetailSaldo);
    $row = $result->fetch_assoc();

    return $row;
}
