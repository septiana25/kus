<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/upload.php';
require_once '../class/barang.php';
require_once '../class/saldo.php';

$uploadClass = new Upload($koneksi);
$barangClass = new Barang($koneksi);
$saldoClass = new Saldo($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());


try {
    $resultKoreksi = handleDataKoreksi($uploadClass, $saldoClass, $barangClass);

    if (!$resultKoreksi['success']) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Data Ada Yang Gagal ";
    } else {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Success! </strong>Data Selesai Dicek";
    }
} catch (\Throwable $th) {
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Data Ada Yang Gagal " . $th->getMessage();
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleDataKoreksi($uploadClass, $saldoClass, $barangClass)
{
    $dataKoreksi = $uploadClass->getDataByIdSaldoNull();
    $results = [
        'success' => false,
    ];

    if ($dataKoreksi->num_rows >= 1) {
        while ($row = $dataKoreksi->fetch_array()) {
            $id_saldo = handleCheckItem($saldoClass, $barangClass, $row['kdbrg'], $row['rak']);
            $update = handleUpdateKoreksiIdSaldo($uploadClass, $row['id'], $id_saldo);
            if (is_null($id_saldo) || !$update['success']) {
                $results['success'] = false;
                break;
            }
            $results['success'] = true;
        }
    }

    return $results;
}

function handleUpdateKoreksiIdSaldo($uploadClass, $id, $id_saldo)
{
    $result = $uploadClass->updateKoreksiIdSaldo($id, $id_saldo);
    return $result;
}

function handleCheckItem($saldoClass, $barangClass, $kdbrg, $rak)
{
    $checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
    $monthSaldoLastDate = SUBSTR($checkSaldoLastDate, 5, -3);
    $yearSaldoLastDate = SUBSTR($checkSaldoLastDate, 0, -6);

    $checkItem = $barangClass->getByItemByRak($monthSaldoLastDate, $yearSaldoLastDate, $kdbrg, $rak);
    $result = $checkItem->fetch_array();
    return $result['id_saldo'];
}
