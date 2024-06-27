<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/upload.php';

$uploadClass = new Upload($koneksi);

try {
    $result = handleFetchKoreksiPlus($uploadClass);

    echo json_encode($result);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
}

function generateButton($id, $id_saldo)
{
    $hrefEdit = is_null($id_saldo) ? "#editModalKoreksiPlus" : "#disableaccess";
    $hrefHapus = is_null($id_saldo) ? "#deleteModalKoreksiPlus" : "#disableaccess";
    $onclickEdit = is_null($id_saldo) ? "editKoreksiPlus($id)" : "";
    $onclickHapus = is_null($id_saldo) ? "deleteKoreksiPlus($id)" : "";

    $button = '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="' . $hrefEdit . '" onclick="' . $onclickEdit . '" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
            <li><a href="#deleteModalKoreksiPlus" onclick="deleteKoreksiPlus(' . $id . ')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
        </ul>
    </div>';

    return $button;
}

function handleFetchKoreksiPlus($uploadClass)
{
    $result = $uploadClass->fetchKoreksiSaldo($type = '2');
    $output = array('data' => array());

    while ($row = $result->fetch_array()) {
        $button = generateButton($row['id'], $row['id_saldo']);
        $status = '<span class="label label-important">Perlu Dicek</span>';
        $status = is_null($row['id_saldo'])
            ? '<span class="label label-important">Perlu Dicek</span>'
            : '<span class="label label-success">OK</span>';
        $output['data'][] = array(
            $row['kdbrg'],
            $row['brg'],
            $row['rak'],
            $row['qty'],
            $status,
            $button
        );
    }

    return $output;
}
