<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';

$detailSaldoClass = new DetailSaldo($koneksi);
$editTahunprod = isset($_POST['editTahunprod']) ? $_POST['editTahunprod'] : '';
$editIdDetail = isset($_POST['editIdDetail']) ? $_POST['editIdDetail'] : '';

$valid['success'] =  array('success' => false, 'messages' => array());
$koneksi->begin_transaction();
$sql_success   = "";

if ($editTahunprod == '') {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleUpdateMonthProd($detailSaldoClass, $editIdDetail, $editTahunprod);

    if (!$result['success']) {
        $valid['success'] = false;
        $valid['messages'] = "Error while updating data";
    }
    $valid['success'] = true;
    $valid['messages'] = "Successfully updated";
    $sql_success .= "success";
} catch (\Throwable $th) {
    error_log($th);
    $valid['success'] = false;
    $valid['messages'] = "An error occurred while fetching data";
} finally {
    if ($sql_success) {
        $koneksi->commit();
    } else {
        $koneksi->rollback();
    }
    $koneksi->close();
    echo json_encode($valid);
}

function handleUpdateMonthProd($detailSaldoClass, $editIdDetail, $editTahunprod)
{
    global $valid;

    $checkDetailSaldoByid = $detailSaldoClass->getDetailSaldoByidDetailsaldo($editIdDetail);
    $data = $checkDetailSaldoByid->fetch_assoc();
    $jumlahLama = $data['jumlah'];
    $id = $data['id'];

    $checkDetailSaldoByMonthYear = $detailSaldoClass->getDetailSaldoByidAndYearProd($id, $editTahunprod);
    if ($checkDetailSaldoByMonthYear->num_rows == 0) {
        $saveDetailSaldo = $detailSaldoClass->save($id, $editTahunprod, $jumlahLama);
        if (!$saveDetailSaldo['success']) {
            $valid['success'] = false;
            return $valid;
        }

        $updateSaldoLama = $detailSaldoClass->update($editIdDetail, 0);
        if ($updateSaldoLama['affected_rows'] == 0) {
            $valid['success'] = false;
            return $valid;
        }

        $valid['success'] = true;
        return $valid;
    }

    $resultCheckDetailSaldoByMonthYear = $checkDetailSaldoByMonthYear->fetch_assoc();
    $idDetail = $resultCheckDetailSaldoByMonthYear['id_detailsaldo'];
    $jumlahBaru = $resultCheckDetailSaldoByMonthYear['jumlah'];
    $jumlahTotalBaru = $jumlahLama + $jumlahBaru;
    $jumlahTotalLama = 0;

    $updateSaldobaru = $detailSaldoClass->update($idDetail, $jumlahTotalBaru);
    if ($updateSaldobaru['affected_rows'] == 0) {
        $valid['success'] = false;
        return $valid;
    }

    $updateSaldoLama = $detailSaldoClass->update($editIdDetail, $jumlahTotalLama);
    if ($updateSaldoLama['affected_rows'] == 0) {
        $valid['success'] = false;
        return $valid;
    }

    $valid['success'] = true;
    return $valid;
}
