<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/barcodebarang.php';
require_once '../class/pomasuk.php';
require_once '../class/masuk.php';


$valid['success'] =  array('success' => false, 'messages' => array());

$koneksi->begin_transaction();
$sql_success   = "";

$barcodeBarang = new BarocdeBarang($koneksi);
$pomasuk = new PoMasuk($koneksi);
$masuk = new Masuk($koneksi);

try {
    $tgl  = trim($koneksi->real_escape_string($_POST["tgl"]));
    $noPO = trim($koneksi->real_escape_string($_POST["nopo"]));
    $barang = trim($koneksi->real_escape_string($_POST["item"]));
    $nopol = trim($koneksi->real_escape_string($_POST["nopol"]));
    $qty = trim($koneksi->real_escape_string($_POST["qty"]));
    $ket = trim(
        $koneksi->real_escape_string(
            isset($_POST["note"]) && !empty($_POST["note"]) ? $_POST["note"] : ""
        )
    );
    $nama = $_SESSION['nama'];

    $checkNoPO = $masuk->getNoPO($noPO, $tgl);

    $checkBarcodeBrg = $barcodeBarang->fetchByItem($barang);
    $resultBarcodeBrg = $checkBarcodeBrg->fetch_array();
    $idBrg  = $resultBarcodeBrg['id_brg'];

    if ($checkNoPO->num_rows == 1) {

        if ($checkBarcodeBrg->num_rows == 1) {
            $resultMasuk = $checkNoPO->fetch_array();
            $idMsk  = $resultMasuk['id_msk'];
            $status = 'INPG';

            $insertPoMasuk = $pomasuk->insertPoMasuk($idMsk, $idBrg, $qty, $nopol, $status, $ket, $nama);

            if ($insertPoMasuk) {
                $valid['success'] = true;
                $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
                $sql_success = true;
            } else {
                $valid['success'] = false;
                $valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan.";
            }
        } else {
            $valid['success'] = false;
            $valid['messages'] = "<strong>Error! </strong> Barang Tidak Ada Barcode.";
        }
    } else if ($checkNoPO->num_rows == 0) {
        if ($checkBarcodeBrg->num_rows == 1) {
            $insertMasuk = $masuk->insertMasuk($noPO, $tgl, $nama);

            if ($insertMasuk['success']) {
                $lastIdMsk  = $insertMasuk['id'];
                $status = 'INPG';

                $insertPoMasuk = $pomasuk->insertPoMasuk($lastIdMsk, $idBrg, $qty, $nopol, $status, $ket, $nama);

                if ($insertPoMasuk) {
                    $valid['success'] = true;
                    $valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
                    $sql_success = true;
                } else {
                    $valid['success'] = false;
                    $valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan.";
                }
            } else {
                $valid['success'] = false;
                $valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan.";
            }
        } else {
            $valid['success'] = false;
            $valid['messages'] = "<strong>Error! </strong> Barang Tidak Ada Barcode.";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Surat Jalan Duplikat.";
    }
} catch (\Throwable $th) {
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Terjadi Kesalahan Hubungi Staf IT.";
} finally {
    if ($sql_success) {

        $koneksi->commit(); //simpan semua data simpan

    } else {

        $koneksi->rollback(); //batal semua data simpan

    }
    $koneksi->close();
    echo json_encode($valid);
}
