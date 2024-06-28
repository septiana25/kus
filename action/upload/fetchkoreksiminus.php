<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/upload.php';

$uploadClass = new Upload($koneksi);

try {
    $result = handleFetchKoreksiMinus($uploadClass);

    echo json_encode($result);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
}

function generateButton($id, $id_saldo)
{
    $hrefEdit = is_null($id_saldo) ? "#editModalKoreksiMinus" : "#disableaccess";
    $hrefHapus = is_null($id_saldo) ? "#deleteModalKoreksiMinus" : "#disableaccess";
    $onclickEdit = is_null($id_saldo) ? "editKoreksiMinus($id)" : "";
    $onclickHapus = is_null($id_saldo) ? "deleteKoreksiMinus($id)" : "";

    $button = '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="' . $hrefEdit . '" onclick="' . $onclickEdit . '" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
            <li><a href="#deleteModalKoreksiMinus" onclick="deleteKoreksiMinus(' . $id . ')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
        </ul>
    </div>';

    return $button;
}

function handleFetchKoreksiMinus($uploadClass)
{
    $result = $uploadClass->fetchKoreksiSaldo($type = '3');
    $output = array('data' => array());

    while ($row = $result->fetch_array()) {
        $button = generateButton($row['id'], $row['id_saldo']);
        $status = !is_null($row['id_saldo']) && !is_null($row['id_detailsaldo'])
            ? '<span class="label label-success">OK</span>'
            : '<span class="label label-important">Perlu Dicek</span>';
        $output['data'][] = array(
            $row['kdbrg'],
            $row['brg'],
            $row['rak'],
            $row['qty'],
            $row['tahunprod'],
            $status,
            $button
        );
    }

    return $output;
}
