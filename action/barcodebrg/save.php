<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$valid['success'] =  array('success' => false, 'messages' => array());

function getBrg($koneksi, $item)
{
    $stmt = $koneksi->prepare("SELECT id_brg, brg FROM barang WHERE brg = ?");
    $stmt->bind_param("s", $item);
    $stmt->execute();
    return $stmt->get_result();
}

function getBrgById($koneksi, $idBrg)
{
    $stmt = $koneksi->prepare("SELECT id_brg FROM barcodebrg WHERE id_brg = ?");
    $stmt->bind_param("s", $idBrg);
    $stmt->execute();
    return $stmt->get_result();
}

function getBarcode($koneksi, $barcode)
{
    $stmt = $koneksi->prepare("SELECT barcode_brg FROM barcodebrg WHERE barcode_brg = ?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    return $stmt->get_result();
}

function insertBarcodeBrg($koneksi, $idBrg, $barcode, $qty, $satuan, $nama)
{
    $stmt = $koneksi->prepare("INSERT INTO barcodebrg (id_brg, barcode_brg, qty, satuan, user) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $idBrg, $barcode, $qty, $satuan, $nama);
    return $stmt->execute();
}

function insertLog($koneksi, $nama, $tgl, $ket, $action)
{
    $stmt = $koneksi->prepare("INSERT INTO log (nama, tgl, ket, action) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $tgl, $ket, $action);
    return $stmt->execute();
}

try {
    $item    = trim($koneksi->real_escape_string($_POST["item"]));
    $qty     = trim($koneksi->real_escape_string($_POST["qty"]));
    $satuan  = trim($koneksi->real_escape_string($_POST["satuan"]));
    $barcode = trim($koneksi->real_escape_string($_POST["barcode"]));

    $nama = $_SESSION['nama'];
    $tgl = date("Y-m-d H:i:s");
    $ket = "Baru " . $barcode;

    $cekBrg = getBrg($koneksi, $item);
    $result = $cekBrg->fetch_array();
    $idBrg  = $result['id_brg'];

    $cekIdBrg = getBrgById($koneksi, $idBrg);

    if ($cekIdBrg->num_rows == 1) {
        $valid['success'] = 'cek_brg';
        $valid['messages'] = "<strong>Error! </strong> Barang Sudah Ada. Tabel Barang Error-AIG-0002 ";
    } else if ($cekIdBrg->num_rows == 0) {
        $cekBarcode = getBarcode($koneksi, $barcode);

        if ($cekBarcode->num_rows == 0) {
            if (insertBarcodeBrg($koneksi, $idBrg, $barcode, $qty, $satuan, $nama)) {
                $valid['success'] = true;
                $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
                insertLog($koneksi, $nama, $tgl, $ket, 't');
            } else {
                $valid['success'] = false;
                $valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan.";
            }
        } else {
            $valid['success'] = 'CheckBarcode';
            $valid['messages'] = "<strong>Error! </strong> Barcode Sudah Ada. ";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Data  Dulikat Hubungi Staf IT.";
    }
} catch (Exception $e) {
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Terjadi Kesalahan Hubungi Staf IT.";
} finally {
    $koneksi->close();
}

echo json_encode($valid);
