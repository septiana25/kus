<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';

$detailSaldoClass = new DetailSaldo($koneksi);
$editTahunprod = isset($_POST['editTahunprod']) ? $_POST['editTahunprod'] : '';
$editIdDetail = isset($_POST['editIdDetail']) ? $_POST['editIdDetail'] : '';

$valid['success'] =  array('success' => false, 'messages' => array());

if ($editTahunprod == '') {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = $detailSaldoClass->updateMonthProd($editIdDetail, $editTahunprod);
    if ($result['affected_rows'] == 0) {
        $valid['success'] = false;
        $valid['messages'] = "Error while updating data";
    }
    $valid['success'] = true;
    $valid['messages'] = "Successfully updated";
    echo json_encode($valid);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}
