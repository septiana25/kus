<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/promosi.php';

$promosiClass = new Promosi($koneksi);

$id_proklr = isset($_POST['id_proklr']) ? $_POST['id_proklr'] : 0;

if ($id_proklr == 0) {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleFetchKoreksiSaldoById($promosiClass, $id_proklr);
    echo json_encode($result);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}

function handleFetchKoreksiSaldoById($promosiClass, $id_proklr)
{

    $result = $promosiClass->getPromosiKeluarById($id_proklr);
    $row = $result->fetch_assoc();

    return $row;
}
