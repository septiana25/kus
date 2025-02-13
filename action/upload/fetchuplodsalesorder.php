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

function generateButton($id, $status, $disabled)
{
    $hrefEdit = $status == "0" ? "#editModalKoreksiSaldo" : "#disableaccess";
    $hrefHapus = $status == "0" && $disabled ? "#deleteModalKoreksiSaldo" : "#disableaccess";
    $onclickEdit = $status == "0" ? "editKoreksiSaldo($id)" : "";
    $onclickHapus = $status == "0" && $disabled ? "deleteKoreksiSaldo($id)" : "";

    $button = '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="' . $hrefEdit . '" onclick="' . $onclickEdit . '" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
            <li><a href="#deleteModalKoreksiSaldo" onclick="deleteKoreksiSaldo(' . $id . ')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
        </ul>
    </div>';

    return $button;
}

function generateLabel($value, $defaultText = 'Tidak Ada', $dangerClass = 'important')
{
    if (is_null($value) || empty($value)) {
        return "<span class=\"label label-{$dangerClass}\">{$defaultText}</span>";
    }
    return $value;
}

function generateStatusLabel($status, $okText = 'OK', $checkText = 'Perlu Dicek')
{
    $labelClass = $status == '1' ? 'success' : 'important';
    $text = $status == '1' ? $okText : $checkText;

    return "<span class=\"label label-{$labelClass}\">{$text}</span>";
}

function handleFetchSalesOrder($soClass)
{
    $result = $soClass->getDataSalesOrderUnprocessed();
    $output = array('data' => array());

    while ($row = $result->fetch_array()) {
        $disabled = $row['sisa'] < $row['qty'] ? true : false;
        $button = generateButton($row['id_so'], $row['status'], $disabled);

        $output['data'][] = array(
            generateLabel($row['supir']),
            generateLabel($row['toko']),
            $row['no_faktur'],
            generateLabel($row['brg']),
            $row['qty'],
            $row['sisa'],
            generateStatusLabel($row['status']),
            $button
        );
    }

    return $output;
}
