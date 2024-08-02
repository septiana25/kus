<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';

$soClass = new Salesorder($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    $result = handleUpdateEkspedisiSalesOrder($soClass, $inputs);
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

function handleUpdateEkspedisiSalesOrder($soClass, $inputs)
{
    $result = $soClass->updateNopolSalesOrder($inputs);
    return $result;
}

function getInputs($koneksi)
{
    $inputs = [
        "id_so" => trim($koneksi->real_escape_string($_POST["ekspedisiid_pro"])),
        "no_faktur" => trim($koneksi->real_escape_string($_POST["noFaktur"])),
        "nopol" => trim($koneksi->real_escape_string($_POST["nopol"]))
    ];

    return $inputs;
}
