<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/barcodebarang.php';

$classBarcode = new BarocdeBarang($koneksi);

$id_brg = isset($_POST['id_brg']) ? $_POST['id_brg'] : 0;

if ($id_brg == 0) {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleFetchKoreksiSaldoById($classBarcode, $id_brg);
    echo json_encode($result);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}

function handleFetchKoreksiSaldoById($classBarcode, $id_brg)
{

    $result = $classBarcode->getById($id_brg);
    $row = $result->fetch_assoc();

    return $row;
}
