<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/barcodebarang.php';
require_once '../class/barang.php';


$valid['success'] =  array('success' => false, 'messages' => array());

$barcodeBarang = new BarocdeBarang($koneksi);
$barang = new Barang($koneksi);

try {
    $inputs = getInputs($koneksi);
    $nama = $_SESSION['nama'];
    extract($inputs);

    $checkItem = $barang->getByItem($item);

    $result = $checkItem->fetch_array();
    $idBrg  = $result['id_brg'];
    $checkIdBarang = $barcodeBarang->getById($idBrg);

    if ($checkIdBarang->num_rows == 1) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Barang Sudah Ada. Tabel Barang Error-AIG-0002 ";
    } else if ($checkIdBarang->num_rows == 0) {
        handleNewItem($barcodeBarang, $idBrg, $barcode, $qty, $satuan, $nama);
    } else {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Data  Dulikat Hubungi Staf IT.";
    }
} catch (Exception $th) {
    error_log($th->getMessage()); // Log the error
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Terjadi Kesalahan Hubungi Staf IT.";
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function getInputs($koneksi)
{
    $inputs = [
        "item"    => trim($koneksi->real_escape_string($_POST["item"])),
        "qty"     => trim($koneksi->real_escape_string($_POST["qty"])),
        "satuan"  => trim($koneksi->real_escape_string($_POST["satuan"])),
        "barcode" => trim($koneksi->real_escape_string($_POST["barcode"])),
    ];

    return $inputs;
}

function handleNewItem($barcodeBarang, $idBrg, $barcode, $qty, $satuan, $nama)
{
    global $valid;

    $checkBarcodeBrg = $barcodeBarang->getByBarcode($barcode);
    if ($checkBarcodeBrg->num_rows != 0) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Barcode Sudah Ada.";
        return $valid;
    }

    $insertBarcode = $barcodeBarang->insertBarcode($idBrg, $barcode, $qty, $satuan, $nama);

    if (!$insertBarcode) {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan di PoMasuk.";
        return $valid;
    }

    $valid['success'] = true;
    $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

    return $valid;
}
