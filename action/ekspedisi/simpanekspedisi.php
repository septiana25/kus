<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/ekspedisi.php';

$ekspedisiClass = new Ekspedisi($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    $result = handleUpdateSalesOrder($ekspedisiClass, $inputs);
    if (!$result['success']) {
        $valid['success'] = false;
        $valid['messages'] = $result['messages'];
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

function handleUpdateSalesOrder($ekspedisiClass, $inputs)
{

    $nopol = $ekspedisiClass->getEkspedisiByNopol($inputs['nopol']);
    if ($nopol->num_rows > 0) {
        return [
            'success' => false,
            'messages' => "<strong>Error! </strong> Plat Nomor Sudah Ada"
        ];
    }

    $result = $ekspedisiClass->insert($inputs);
    if ($result) {
        return [
            'success' => true
        ];
    } else {
        return [
            'success' => false,
            'messages' => "<strong>Error! </strong> Gagal Disimpan"
        ];
    }
}

function getInputs($koneksi)
{
    $inputs = [
        "nopol" => trim($koneksi->real_escape_string($_POST["nopol"])),
        "supir" => trim($koneksi->real_escape_string($_POST["supir"])),
        "jenis" => trim($koneksi->real_escape_string($_POST["jenis"]))
    ];

    return $inputs;
}
