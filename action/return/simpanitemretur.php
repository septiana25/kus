<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/dataretur.php';

$classRetur = new DataRetur($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $inputs = getInputs($koneksi);
    $result = handleAddItemRetur($classRetur, $inputs);
    if ($result) {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Error! </strong> Data Berhasil Di Simpan";
    } else {
        throw new Exception("Data Gagal Disimpan");
    }
} catch (Exception $e) {
    $valid['success'] = false;
    $valid['messages'] = $e->getMessage();
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleAddItemRetur($classRetur, $inputs)
{
    handleValidateInputs($inputs);
    return $classRetur->insertItemRetur($inputs);
}


function handleValidateInputs($inputs)
{
    // Validasi barang
    if (empty($inputs['barang'])) {
        throw new Exception("Barang tidak boleh kosong");
    }

    // Validasi rak
    if (empty($inputs['rak'])) {
        throw new Exception("Rak tidak boleh kosong");
    }

    // Validasi quantity
    if (empty($inputs['qty'])) {
        throw new Exception("Quantity tidak boleh kosong");
    }

    if (!is_numeric($inputs['qty'])) {
        throw new Exception("Quantity harus berupa angka");
    }

    if ($inputs['qty'] <= 0) {
        throw new Exception("Quantity harus lebih besar dari 0");
    }

    return true;
}

function getInputs($koneksi)
{
    $inputs = [
        "user" => $_SESSION['nama'],
        "barang" => trim($koneksi->real_escape_string($_POST["barang"])),
        "rak" => trim($koneksi->real_escape_string($_POST["addrak"])),
        "qty" => trim($koneksi->real_escape_string($_POST["addqty"]))
    ];
    return $inputs;
}
