<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$valid['success'] =  array('success' => false, 'messages' => array());

function getRak($koneksi, $rak)
{
    $stmt = $koneksi->prepare("SELECT id_rak, rak FROM rak WHERE rak = ?");
    $stmt->bind_param("s", $rak);
    $stmt->execute();
    return $stmt->get_result();
}

function getRakById($koneksi, $idRak)
{
    $stmt = $koneksi->prepare("SELECT id_rak FROM barcoderak WHERE id_rak = ?");
    $stmt->bind_param("s", $idRak);
    $stmt->execute();
    return $stmt->get_result();
}

function getBarcode($koneksi, $barcode)
{
    $stmt = $koneksi->prepare("SELECT barcode_rak FROM barcoderak WHERE barcode_rak = ?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    return $stmt->get_result();
}

function insertBarcodeRak($koneksi, $idRak, $barcode, $nama)
{
    $stmt = $koneksi->prepare("INSERT INTO barcoderak (id_rak, barcode_rak, user) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $idRak, $barcode, $nama);
    return $stmt->execute();
}

function insertLog($koneksi, $nama, $tgl, $ket, $action)
{
    $stmt = $koneksi->prepare("INSERT INTO log (nama, tgl, ket, action) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $tgl, $ket, $action);
    return $stmt->execute();
}

try {
    $rak    = trim($koneksi->real_escape_string($_POST["rak"]));
    $barcode = trim($koneksi->real_escape_string($_POST["barcode"]));

    $nama = $_SESSION['nama'];
    $tgl = date("Y-m-d H:i:s");
    $ket = "Rak " . $barcode;

    $rak = getRak($koneksi, $rak);
    $result = $rak->fetch_array();
    $idRak  = $result['id_rak'];

    $cekIdRak = getRakById($koneksi, $idRak);

    if ($cekIdRak->num_rows == 1) {
        $valid['success'] = 'false';
        $valid['messages'] = "<strong>Error! </strong> Rak Sudah Ada. Tabel Barang Error-AIG-0002 ";
    } else if ($cekIdRak->num_rows == 0) {
        $cekBarcode = getBarcode($koneksi, $barcode);

        if ($cekBarcode->num_rows == 0) {
            if (insertBarcodeRak($koneksi, $idRak, $barcode, $nama)) {
                $valid['success'] = true;
                $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
                insertLog($koneksi, $nama, $tgl, $ket, 't');
            } else {
                $valid['success'] = false;
                $valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan.";
            }
        } else {
            $valid['success'] = false;
            $valid['messages'] = "<strong>Error! </strong> Barcode Sudah Ada. ";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Data Dulikat Hubungi Staf IT.";
    }
} catch (Exception $e) {
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Terjadi Kesalahan Hubungi Staf IT.";
} finally {
    $koneksi->close();
}

echo json_encode($valid);
