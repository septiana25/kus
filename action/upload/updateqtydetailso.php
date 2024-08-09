<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';
require_once '../class/salesorder.php';
require_once '../class/saldo.php';

$detailSaldoClass = new DetailSaldo($koneksi);
$soClass = new Salesorder($koneksi);
$saldoClass = new Saldo($koneksi);

$koneksi->begin_transaction();
$conn = $koneksi;

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    // Periksa apakah $inputs adalah array dan memiliki kunci 'success'
    if (is_array($inputs) && array_key_exists('success', $inputs)) {
        if (!$inputs['success']) {
            $valid['success'] = false;
            $valid['messages'] = $inputs['messages'];
            echo json_encode($valid);
            die();
        }
    }

    $result = handleUpdateDetailSaldo($detailSaldoClass, $saldoClass, $soClass, $inputs, $conn);

    if (!$result['success']) {
        $valid['success'] = false;
        $valid['messages'] = $result['messages'];
    } else {

        $valid['success'] = true;
        $valid['messages'] = "Successfully updated";
    }
} catch (\Throwable $th) {
    $valid['success'] = false;
    $valid['messages'] = $th->getMessage();
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleUpdateDetailSaldo($detailSaldoClass, $saldoClass, $soClass, $inputs, $conn)
{
    global $valid;

    $checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
    $monthSaldoLastDate = SUBSTR($checkSaldoLastDate, 5, -3);
    $yearSaldoLastDate = SUBSTR($checkSaldoLastDate, 0, -6);

    $checkDetailSO = $soClass->getDataDetailProsessSalesOrderByIdPro($inputs['id_pro']);
    $resultCheckDetailSO = $checkDetailSO->fetch_assoc();
    $id_detailsaldo = $resultCheckDetailSO['id_detailsaldo'];


    $checkIdDetailSaldo = $detailSaldoClass->getDetailSaldoByidDetailsaldo($id_detailsaldo);
    $resultCheckIdDetailSaldo = $checkIdDetailSaldo->fetch_assoc();
    $id = $resultCheckIdDetailSaldo['id'];

    $checkSaldo = $saldoClass->getSaldoByidJoinDetail($id, $monthSaldoLastDate, $yearSaldoLastDate);
    $resultcheckSaldo = $checkSaldo->fetch_assoc();

    $qty_pro = $resultCheckDetailSO['qty_pro'];
    $selisi = $qty_pro - $inputs['qty'];
    //$sisaSaldo = $selisiawal + $inputs['qty'];

    if ($inputs['qty'] > $qty_pro) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Quantity Terlalu Besar.";
        return $valid;
    }

    $updateQtyDetailSaldo = $detailSaldoClass->updatePlus($id_detailsaldo, $selisi);
    if (!$updateQtyDetailSaldo['success']) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Gagal Update Detail Saldo.";
        return $valid;
    }

    $updateSaldo = $saldoClass->updateSaldoPlus($resultcheckSaldo['id_saldo'], $selisi);
    if (!$updateSaldo['success']) {
        $conn->rollback();
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Gagal Update Saldo.";
        return $valid;
    }

    $updateSO = $soClass->updateQtyProssesSalesOrder($inputs['id_pro'], $inputs['qty']);
    if (!$updateSO['success']) {
        $conn->rollback();
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Gagal Update Sales Order.";
        return $valid;
    }

    $conn->commit();
    $valid['success'] = true;

    return $valid;
}

function getInputs($koneksi)
{
    global $valid;
    $inputs = [];
    $errors = [];

    $requiredFields = [
        "qtyid_pro" => "ID Produk",
        "qty" => "Kuantitas"
    ];

    foreach ($requiredFields as $field => $fieldName) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            $errors[] = "$fieldName tidak boleh kosong.";
        } else {
            $inputs[$field] = trim($koneksi->real_escape_string($_POST[$field]));
        }
    }

    if (!empty($errors)) {
        $valid['success'] = false;
        $valid['messages'] = implode(" ", $errors);
        return $valid;
    }

    // Validasi tambahan untuk qty (harus berupa angka positif)
    if (!is_numeric($inputs['qty']) || $inputs['qty'] <= 0) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Quantity Harus Berupa Angka Positif.";
        return $valid;
    }

    return [
        "id_pro" => $inputs['qtyid_pro'],
        "qty" => $inputs['qty']
    ];
}
