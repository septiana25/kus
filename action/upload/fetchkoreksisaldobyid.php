<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/upload.php';


$uploadClass = new Upload($koneksi);
$id = isset($_POST['id']) ? $_POST['id'] : 0;

if ($id == 0) {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleFetchKoreksiSaldoById($uploadClass, $id);
    echo json_encode($result);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}

function handleFetchKoreksiSaldoById($uploadClass, $id)
{
    $result = $uploadClass->fetchKoreksiSaldoByid($id);
    $row = $result->fetch_assoc();

    return $row;
}
