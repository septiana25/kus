<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/detailsaldo.php';
require_once '../class/saldo.php';

$valid['success'] =  array('success' => false, 'messages' => array());
$koneksi->begin_transaction();
$sql_success   = "";

$detailsaldoClass = new DetailSaldo($koneksi);
$saldoClass = new Saldo($koneksi);

try {
    $inputs = getInputs($koneksi);
    extract($inputs);
    handleSaveDetailSaldo($detailsaldoClass, $saldoClass, $id, $tahunprod, $qty);
} catch (\Throwable $th) {
    error_log($th->getMessage()); // Log the error
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Terjadi Kesalahan Hubungi Staf IT.";
} finally {
    if ($sql_success) {
        $koneksi->commit();
    } else {
        $koneksi->rollback();
    }

    $koneksi->close();
    echo json_encode($valid);
}

function getInputs($koneksi)
{
    $inputs = [
        "id" => trim($koneksi->real_escape_string($_POST["id"])),
        "tahunprod" => trim($koneksi->real_escape_string($_POST["tahunprod"])),
        "qty" => trim($koneksi->real_escape_string($_POST["qty"])),
    ];

    return $inputs;
}


function handleSaveDetailSaldo($detailsaldoClass, $saldoClass, $id, $tahunprod, $qty)
{
    global $valid, $sql_success;

    $checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
    $monthSaldoLastDate = SUBSTR($checkSaldoLastDate, 5, -3);
    $yearSaldoLastDate = SUBSTR($checkSaldoLastDate, 0, -6);
    $checkSaldo = $saldoClass->getSaldoByidJoinDetail($id, $monthSaldoLastDate, $yearSaldoLastDate);
    $checkDetailSaldo = $detailsaldoClass->getDetailSaldoByidAndYearProd($id, $tahunprod);

    $resultcheckSaldo = $checkSaldo->fetch_array();
    $saldoAkhir = $resultcheckSaldo['saldo_akhir'];
    $saldoSisaTahunProd = $resultcheckSaldo['subtotal'] + $qty;

    if ($saldoSisaTahunProd > $saldoAkhir) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Quantity Lebih Besar Dari Sisa.";
        return $valid;
    }

    if ($checkDetailSaldo->num_rows == 1) {
        $resultDetailSaldo = $checkDetailSaldo->fetch_array();
        $idDetailSaldo = $resultDetailSaldo['id_detailsaldo'];
        $totalQty = $resultDetailSaldo['jumlah'] + $qty;
        $updateDetailSaldo = $detailsaldoClass->update($idDetailSaldo, $totalQty);
        if ($updateDetailSaldo['success']) {
            $valid['success'] = true;
            $valid['messages'] = "<strong>Success! </strong>Data Berhasil Diupdate";
            $sql_success .= "success";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "<strong>Error! </strong> Terjadi Kesalahan Hubungi Staf IT.";
        }
    } else {
        $saveDetailSaldo = $detailsaldoClass->save($id, $tahunprod, $qty);
        if ($saveDetailSaldo['success']) {
            $valid['success'] = true;
            $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
            $sql_success .= "success";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "<strong>Error! </strong> Terjadi Kesalahan Hubungi Staf IT.";
        }
    }

    return $valid;
}
