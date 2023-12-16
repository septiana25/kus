<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/barcodebarang.php';
require_once '../class/pomasuk.php';
require_once '../class/masuk.php';

define('STATUS', 'INPG');

$valid['success'] =  array('success' => false, 'messages' => array());

$koneksi->begin_transaction();
$sql_success   = "";

$barcodeBarang = new BarocdeBarang($koneksi);
$pomasuk = new PoMasuk($koneksi);
$masuk = new Masuk($koneksi);

try {
    $inputs = getInputs($koneksi);
    $nama = $_SESSION['nama'];
    extract($inputs);

    $checkNoPO = $masuk->getNoPO($noPO, $tgl);

    $checkBarcodeBrg = $barcodeBarang->getByItem($barang);
    $resultBarcodeBrg = $checkBarcodeBrg->fetch_array();
    $idBrg  = $resultBarcodeBrg['id_brg'];

    if ($checkNoPO->num_rows == 1) {
        handleExistingNoPO($checkBarcodeBrg, $checkNoPO, $pomasuk, $idBrg, $qty, $nopol, $ket, $nama);
    } else if ($checkNoPO->num_rows == 0) {
        handleNewNoPO($checkBarcodeBrg, $masuk, $pomasuk, $noPO, $tgl, $nama, $idBrg, $qty, $nopol, $ket);
    } else {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Surat Jalan Duplikat.";
    }
} catch (\Throwable $th) {
    error_log($th->getMessage()); // Log the error
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Terjadi Kesalahan Hubungi Staf IT.";
} finally {
    if ($sql_success) {
        $koneksi->commit();
    } else {
        $koneksi->rollback();
    }
    $koneksi->close();
    echo json_encode($valid);
}

function getInputs($koneksi)
{
    $inputs = [
        "tgl" => trim($koneksi->real_escape_string($_POST["tgl"])),
        "noPO" => trim($koneksi->real_escape_string($_POST["nopo"])),
        "barang" => trim($koneksi->real_escape_string($_POST["item"])),
        "nopol" => trim($koneksi->real_escape_string($_POST["nopol"])),
        "qty" => trim($koneksi->real_escape_string($_POST["qty"])),
        "ket" => trim(
            $koneksi->real_escape_string(
                isset($_POST["note"]) && !empty($_POST["note"]) ? $_POST["note"] : ""
            )
        )
    ];

    return $inputs;
}

function handleExistingNoPO($checkBarcodeBrg, $checkNoPO, $pomasuk, $idBrg, $qty, $nopol, $ket, $nama)
{
    global $valid, $sql_success;
    if ($checkBarcodeBrg->num_rows != 1) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Barang Tidak Ada Barcode.";
        return $valid;
    }

    $resultMasuk = $checkNoPO->fetch_array();
    $idMsk  = $resultMasuk['id_msk'];
    $status = STATUS;

    $insertPoMasuk = $pomasuk->insertPoMasuk($idMsk, $idBrg, $qty, $nopol, $status, $ket, $nama);

    if (!$insertPoMasuk['success']) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan di PoMasuk.";
        return $valid;
    }

    $valid['success'] = true;
    $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
    $sql_success = true;

    return $valid;
}

function handleNewNoPO($checkBarcodeBrg, $masuk, $pomasuk, $noPO, $tgl, $nama, $idBrg, $qty, $nopol, $ket)
{
    global $valid, $sql_success;

    if ($checkBarcodeBrg->num_rows != 1) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Barang Tidak Ada Barcode.";
        return $valid;
    }

    $insertMasuk = $masuk->insertMasuk($noPO, $tgl, $nama);

    if (!$insertMasuk['success']) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan di Masuk.";
        return $valid;
    }

    $lastIdMsk  = $insertMasuk['id'];
    $status = STATUS;

    $insertPoMasuk = $pomasuk->insertPoMasuk($lastIdMsk, $idBrg, $qty, $nopol, $status, $ket, $nama);

    if (!$insertPoMasuk) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan di PoMasuk.";
        return $valid;
    }

    $valid['success'] = true;
    $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
    $sql_success = true;

    return $valid;
}
