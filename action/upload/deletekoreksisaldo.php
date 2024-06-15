<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/upload.php';

$uploadClass = new Upload($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    $result = handleHapusKoreksiSaldo($uploadClass, $inputs['id']);
    if (!$result['success']) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Hapus Data Gagal";
    } else {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Success! </strong>Data Berhasil Dihapus";
    }
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleHapusKoreksiSaldo($uploadClass, $id)
{
    $date = date('Y-m-d H:i:s');
    $result = $uploadClass->delete($id, $date);

    return $result;
}


function getInputs($koneksi)
{
    $inputs = [
        "id" => trim($koneksi->real_escape_string($_POST["hapusid"]))
    ];

    return $inputs;
}
