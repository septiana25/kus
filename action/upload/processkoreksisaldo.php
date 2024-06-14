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
$koneksi->begin_transaction();
$sql_success   = "";

try {
    $resultKoreksi = handleDataKoreksi($uploadClass, $saldoClass, $barangClass);
    if (!$resultKoreksi['success']) {
        $valid['success'] = false;
        $valid['messages'] = $resultKoreksi['messages'];
    } else {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Success! </strong>Data Selesai Diproses";
        $sql_success .= "success";
    }
} catch (\Throwable $th) {
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Data Ada Yang Gagal";
} finally {
    if ($sql_success) {
        $koneksi->commit();
    } else {
        $koneksi->rollback();
    }
    $koneksi->close();
    echo json_encode($valid);
}

function handleDataKoreksi($uploadClass, $saldoClass)
{
    $dataKoreksi = $uploadClass->getDataByIdSaldoNotNull();
    $results = [
        'success' => true,
        'messages' => []
    ];

    if ($dataKoreksi->num_rows >= 1) {
        while ($row = $dataKoreksi->fetch_array()) {
            $saldoAwal = handleSaldoAkhir($saldoClass, $row['id_saldo']);
            $prosesKoreksi = handleUpdateSaldoByKoreksi($saldoClass, $row['id_saldo'], $row['qty']);
            if (!$prosesKoreksi['success']) {
                $results['success'] = false;
                $results['messages'] = "<strong>Error! </strong> Update Saldo " . $row['brg'] . " Gagal";
                break;
            }
            $updateStatus = handleUpdateStatusKoreksi($uploadClass, $row['id'], $saldoAwal);
            if (!$updateStatus['success']) {
                $results['success'] = false;
                $results['errors'] = "<strong>Error! </strong> Update Status " . $row['brg'] . " Gagal";
                break;
            }
        }
    }

    return $results;
}

function handleUpdateSaldoByKoreksi($saldoClass, $id_saldo, $qty)
{
    $result = $saldoClass->updateSaldoByKoreksi($id_saldo, $qty);
    return $result;
}

function handleUpdateStatusKoreksi($uploadClass, $id, $saldoAwal)
{
    $atUpdate = date('Y-m-d H:i:s');
    $result = $uploadClass->updateStatusKoreksi($id, $saldoAwal, $atUpdate);
    return $result;
}

function handleSaldoAkhir($saldoClass, $id_saldo)
{
    $saldo = $saldoClass->getSaldoByIdSaldo($id_saldo);
    $saldoFetch = $saldo->fetch_assoc();
    $saldoFetch['saldo_akhir'];
    return $saldoFetch['saldo_akhir'];
}
