<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';
require_once '../class/saldo.php';

$detailSaldoClass = new DetailSaldo($koneksi);
$saldoClass = new Saldo($koneksi);

$editQtyDetailSaldo = isset($_POST['editQtyDetailSaldo']) ? $_POST['editQtyDetailSaldo'] : '';
$editIdDetail = isset($_POST['editIdDetail']) ? $_POST['editIdDetail'] : '';

$valid['success'] =  array('success' => false, 'messages' => array());

if ($editQtyDetailSaldo == '') {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleUpdateDetailSaldo($detailSaldoClass, $saldoClass, $editIdDetail, $editQtyDetailSaldo);
    if (!$result['success']) {
        $valid['success'] = false;
        $valid['messages'] = $result['messages'];
    } else {

        $valid['success'] = true;
        $valid['messages'] = "Successfully updated";
    }
} catch (\Throwable $th) {
    error_log($th);
    $valid['success'] = false;
    $valid['messages'] = "An error occurred while fetching data";
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleUpdateDetailSaldo($detailSaldoClass, $saldoClass, $editIdDetail, $editQtyDetailSaldo)
{
    global $valid;

    $checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
    $monthSaldoLastDate = SUBSTR($checkSaldoLastDate, 5, -3);
    $yearSaldoLastDate = SUBSTR($checkSaldoLastDate, 0, -6);

    $checkIdDetailSaldo = $detailSaldoClass->getDetailSaldoByidDetailsaldo($editIdDetail);
    $resultCheckIdDetailSaldo = $checkIdDetailSaldo->fetch_array();
    $id = $resultCheckIdDetailSaldo['id'];

    $checkSaldo = $saldoClass->getSaldoByidJoinDetail($id, $monthSaldoLastDate, $yearSaldoLastDate);
    $resultcheckSaldo = $checkSaldo->fetch_array();
    $saldoAkhir = $resultcheckSaldo['saldo_akhir'];
    $saldoSisaTahunProd = $resultcheckSaldo['subtotal'] - $resultCheckIdDetailSaldo['jumlah'];
    $sisaSaldo = $saldoSisaTahunProd + $editQtyDetailSaldo;

    if ($sisaSaldo > $saldoAkhir) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Quantity Lebih Besar Dari Sisa.";
        return $valid;
    }

    $updateQtyDetailSaldo = $detailSaldoClass->update($editIdDetail, $editQtyDetailSaldo);
    if (!$updateQtyDetailSaldo['success']) {
        $valid['success'] = false;
        $valid['messages'] = "Data Tidak Berubah";
    } else {
        $valid['success'] = true;
    }

    return $valid;
}
