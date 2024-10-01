<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/promosi.php';

$promosiClass = new Promosi($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $koneksi->begin_transaction();
    $inputs = getInputs($koneksi);
    $inputs['user'] = $_SESSION['nama'];
    $inputs['no_tran'] = $inputs['noAwal'] . '-0000' . $inputs['noAkhir'];
    $result = handleInsertPromosiKeluar($promosiClass, $inputs);
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
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleInsertPromosiKeluar($promosiClass, $inputs)
{
    $item = $promosiClass->fetchPromosiByid($inputs['item']);
    if ($item->num_rows == 0) {
        return [
            'success' => false,
            'messages' => "<strong>Error! </strong> Item Tidak Ada"
        ];
    }

    $promosi = $promosiClass->getPromosiByNoTran($inputs['no_tran']);
    $resultPromosi = $promosi->fetch_assoc();
    $at_create = new DateTime($resultPromosi['at_create']);
    $now = new DateTime();

    if ($resultPromosi['no_trank'] == $inputs['no_tran']) {
        if ($at_create->format('Y-m-d') !== $now->format('Y-m-d')) {
            return [
                'success' => false,
                'messages' => "<strong>Error! </strong> No Transaksi Sudah Ada"
            ];
        }

        if ($at_create->format('Y-m-d') == $now->format('Y-m-d')) {

            if ($resultPromosi['id_toko'] != $inputs['toko']) {
                return [
                    'success' => false,
                    'messages' => "<strong>Error! </strong> No Transaksi Sudah Ada"
                ];
            }
        }
    }

    $result = $promosiClass->insertPromosiKeluar($inputs);
    $updateSaldo = $promosiClass->updateSaldoKeluar($inputs['item'], $inputs['qty']);
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
        "qty" => trim($koneksi->real_escape_string($_POST["qty"])),
        "item" => trim($koneksi->real_escape_string($_POST["item"])),
        "toko" => trim($koneksi->real_escape_string($_POST["toko"])),
        "note" => trim($koneksi->real_escape_string($_POST["note"])),
        "sales" => trim($koneksi->real_escape_string($_POST["sales"])),
        "divisi" => trim($koneksi->real_escape_string($_POST["divisi"])),
        "noAwal" => trim($koneksi->real_escape_string($_POST["noAwal"])),
        "noAkhir" => trim($koneksi->real_escape_string($_POST["noAkhir"]))
    ];

    return $inputs;
}
