<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/upload.php';

$uploadClass = new Upload($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    $result = handleUpdateKoreksiSaldo($uploadClass, $inputs);
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

function handleUpdateKoreksiSaldo($uploadClass, $inputs)
{
    $result = $uploadClass->update($inputs);
    return $result;
}


function getInputs($koneksi)
{
    $inputs = [
        "id" => trim($koneksi->real_escape_string($_POST["id"])),
        "kdbrg" => trim($koneksi->real_escape_string($_POST["kdbrg"])),
        "rak" => trim($koneksi->real_escape_string($_POST["rak"])),
        "tahunprod" => trim($koneksi->real_escape_string($_POST["tahunprod"]))
    ];

    return $inputs;
}
