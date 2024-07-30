<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';

$soClass = new Salesorder($koneksi);

try {
    $nopol = trim($koneksi->real_escape_string($_GET['expedition']));
    if (!isset($nopol) || empty($nopol)) {
        header('location:../../uploadsalesorder.php');
    }

    $result = handleFetchProsessSalesOrder($soClass, $nopol);

    echo json_encode($result);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
}

function generateButton($id_pro)
{
    $button = '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#editModalQtySO" onclick="editQty(' . $id_pro . ')" data-toggle="modal"><i class="icon-pencil" aria-hidden="true"></i> Qty</a></li>
            <li><a href="#editModalEkspedisiSO" onclick="editEkspedisi(' . $id_pro . ')" data-toggle="modal"><i class="fa fa-truck" aria-hidden="true"></i> Ekspedisi</a></li>
        </ul>
    </div>';

    return $button;
}

function handleFetchProsessSalesOrder($soClass, $nopol)
{
    $result = $soClass->getDataDetailProsessSalesOrder($nopol);
    $output = array('data' => array());

    while ($row = $result->fetch_array()) {
        $button = generateButton($row['id_pro']);

        $output['data'][] = array(
            $row['supir'],
            $row['no_faktur'],
            $row['toko'],
            $row['brg'],
            $row['rak'],
            $row['tahunprod'],
            $row['qty_pro'],
            $button
        );
    }

    return $output;
}
