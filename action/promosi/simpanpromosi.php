<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/promosi.php';

$promosiClass = new Promosi($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    $result = handleInsertPromosi($promosiClass, $inputs);
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

function handleInsertPromosi($promosiClass, $inputs)
{

    $item = $promosiClass->getPromosiByItem($inputs['item']);
    if ($item->num_rows > 0) {
        return [
            'success' => false,
            'messages' => "<strong>Error! </strong> Plat Nomor Sudah Ada"
        ];
    }

    $result = $promosiClass->insert($inputs);
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
        "divisi" => trim($koneksi->real_escape_string($_POST["divisi"])),
        "item" => trim($koneksi->real_escape_string($_POST["item"])),
        "jenis" => trim($koneksi->real_escape_string($_POST["jenis"])),
        "note" => trim($koneksi->real_escape_string($_POST["note"]))
    ];

    return $inputs;
}
