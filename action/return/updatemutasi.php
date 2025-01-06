<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/mutasi.php';

$classMutasi = new Mutasi($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    $result = handleCloseMutasi($classMutasi, $inputs);
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

function handleCloseMutasi($classMutasi, $inputs)
{
    $result = $classMutasi->update($inputs);

    return $result;
}


function getInputs($koneksi)
{
    $date = date('Y-m-d H:i:s');
    $inputs = [
        "id" => trim($koneksi->real_escape_string($_POST["id_mutasi"])),
        "date" => $date
    ];

    return $inputs;
}
