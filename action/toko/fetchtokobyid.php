<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/toko.php';

$tokoClass = new Toko($koneksi);

$id_toko = isset($_POST['id_toko']) ? $_POST['id_toko'] : 0;

if ($id_toko == 0) {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleFetchTokoById($tokoClass, $id_toko);
    echo json_encode($result);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}

function handleFetchTokoById($tokoClass, $id_toko)
{

    $result = $tokoClass->getTokoById($id_toko);
    $row = $result->fetch_assoc();

    return $row;
}
