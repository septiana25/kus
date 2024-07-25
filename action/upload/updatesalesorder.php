<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';

$soClass = new Salesorder($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    $result = handleUpdateSalesOrder($soClass, $inputs);
    if (!$result['success']) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Update Data Gagal";
    } else {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
    }
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleUpdateSalesOrder($soClass, $inputs)
{
    $result = $soClass->update($inputs);
    return $result;
}

function getInputs($koneksi)
{
    $inputs = [
        "id_so" => trim($koneksi->real_escape_string($_POST["id_so"])),
        "nopol" => trim($koneksi->real_escape_string($_POST["nopol"])),
        "kode_toko" => trim($koneksi->real_escape_string($_POST["kode_toko"])),
        "kdbrg" => trim($koneksi->real_escape_string($_POST["kdbrg"])),
        "qty" => trim($koneksi->real_escape_string($_POST["qty"]))
    ];

    return $inputs;
}
