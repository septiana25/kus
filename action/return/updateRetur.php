<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/dataretur.php';

$classRetur = new DataRetur($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    $result = handleCloseRetur($classRetur, $inputs['id']);
    if (!$result['success']) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Close Data Gagal";
    } else {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Success! </strong>Data Berhasil Di Close";
    }
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleCloseRetur($classRetur, $id)
{

    $result = $classRetur->update($id);

    return $result;
}


function getInputs($koneksi)
{
    $inputs = [
        "id" => trim($koneksi->real_escape_string($_POST["id_retur"]))
    ];

    return $inputs;
}
