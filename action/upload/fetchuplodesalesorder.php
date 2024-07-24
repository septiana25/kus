<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';

$soClass = new Salesorder($koneksi);

try {
    $result = handleFetchSalesOrder($soClass);

    echo json_encode($result);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
}

function generateButton($id, $status)
{
    $hrefEdit = $status == "0" ? "#editModalKoreksiSaldo" : "#disableaccess";
    $hrefHapus = $status == "0" ? "#deleteModalKoreksiSaldo" : "#disableaccess";
    $onclickEdit = $status == "0" ? "editKoreksiSaldo($id)" : "";
    $onclickHapus = $status == "0" ? "deleteKoreksiSaldo($id)" : "";

    $button = '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="' . $hrefEdit . '" onclick="' . $onclickEdit . '" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
            <li><a href="#deleteModalKoreksiSaldo" onclick="deleteKoreksiSaldo(' . $id . ')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
        </ul>
    </div>';

    return $button;
}

function handleFetchSalesOrder($soClass)
{
    $result = $soClass->getDataSalesOrderUnprocessed();
    $output = array('data' => array());

    while ($row = $result->fetch_array()) {
        $button = generateButton($row['id_so'], $row['status']);

        $status = $row['status'] == '1'
            ? '<span class="label label-success">OK</span>'
            : '<span class="label label-important">Perlu Dicek</span>';

        $toko = is_null($row['toko'])
            ? '<span class="label label-important">Tida Ada</span>'
            : $row['toko'];

        $barang = is_null($row['brg'])
            ? '<span class="label label-important">Tida Ada</span>'
            : $row['brg'];

        $ekspedisi = is_null($row['nopol'])
            ? '<span class="label label-important">Tida Ada</span>'
            : $row['nopol'];

        $output['data'][] = array(
            $ekspedisi,
            $toko,
            $row['no_faktur'],
            $barang,
            $row['qty'],
            $status,
            $button
        );
    }

    return $output;
}
