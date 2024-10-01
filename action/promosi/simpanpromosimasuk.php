<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/promosi.php';

$promosiClass = new Promosi($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $koneksi->begin_transaction();
    $inputs = getInputs($koneksi);
    $inputs['user'] = $_SESSION['nama'];
    $inputs['no_tran'] = $inputs['noAwal'] . '-0000' . $inputs['noAkhir'];
    $result = handleInsertPromosiMasuk($promosiClass, $inputs);
    if (!$result['success']) {
        $valid['success'] = false;
        $valid['messages'] = $result['messages'];
        $koneksi->rollback();
    } else {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
        $koneksi->commit();
    }
} catch (Exception $e) {
    $koneksi->rollback();
    echo $e->getMessage();
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleInsertPromosiMasuk($promosiClass, $inputs)
{

    $item = $promosiClass->fetchPromosiByid($inputs['item']);
    if ($item->num_rows == 0) {
        return [
            'success' => false,
            'messages' => "<strong>Error! </strong> Item Tidak Ada"
        ];
    }

    $result = $promosiClass->insertPromosiMasuk($inputs);
    $updateSaldo = $promosiClass->updateSaldo($inputs['item'], $inputs['qty']);
    if ($result && $updateSaldo) {
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
        "qty" => trim($koneksi->real_escape_string($_POST["qty"])),
        "note" => trim($koneksi->real_escape_string($_POST["note"])),
        "noAwal" => trim($koneksi->real_escape_string($_POST["noAwal"])),
        "noAkhir" => trim($koneksi->real_escape_string($_POST["noAkhir"]))
    ];

    return $inputs;
}
