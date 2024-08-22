<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/barcodebarang.php';

$classBarcode = new BarocdeBarang($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    $result = handleUpdateBarcode($classBarcode, $inputs);
    if (!$result['success']) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Update Data Gagal";
    } else {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
    }
} catch (Exception $e) {
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> " . $e->getMessage();
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleUpdateBarcode($classBarcode, $inputs)
{
    $result = $classBarcode->updateBarcode($inputs);
    return $result;
}

function getInputs($koneksi)
{
    $inputs = [
        "id_brg" => trim($koneksi->real_escape_string($_POST["id_brg"])),
        "barcode_brg" => trim($koneksi->real_escape_string($_POST["barcodebrg"])),
        "brg" => trim($koneksi->real_escape_string($_POST["brg"])),
        "satuan" => trim($koneksi->real_escape_string($_POST["satuan"])),
        "qty" => trim($koneksi->real_escape_string($_POST["qty"])),
        "user" => $_SESSION['nama']
    ];

    return $inputs;
}
