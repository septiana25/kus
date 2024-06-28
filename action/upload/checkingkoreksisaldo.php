<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/upload.php';
require_once '../class/barang.php';
require_once '../class/saldo.php';
require_once '../class/detailsaldo.php';

$uploadClass = new Upload($koneksi);
$barangClass = new Barang($koneksi);
$saldoClass = new Saldo($koneksi);
$detailsaldoClass = new DetailSaldo($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());


try {
    $type = trim($koneksi->real_escape_string($_POST['type']));
    $resultKoreksi = handleDataKoreksi($uploadClass, $saldoClass, $barangClass, $detailsaldoClass, $type);

    if (!$resultKoreksi['success']) {
        $valid['success'] = false;
        $valid['messages'] = $resultKoreksi['messages'];
    } else {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Success! </strong>Data Selesai Dicek";
    }
} catch (\Throwable $th) {
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Data Ada Yang Gagal " . $th->getMessage();
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleDataKoreksi($uploadClass, $saldoClass, $barangClass, $detailsaldoClass, $type)
{
    $dataKoreksi = $uploadClass->getDataByIdSaldoNull($type);
    $results = [
        'success' => false,
        'messages' => []
    ];

    if ($dataKoreksi->num_rows >= 1) {
        while ($row = $dataKoreksi->fetch_array()) {
            $checkItem = handleCheckItem($saldoClass, $barangClass, $row['kdbrg'], $row['rak']);
            if (is_null($checkItem)) {
                $results['success'] = false;
                $results['messages'] = "<strong>Error! </strong> Kode Barang atau Rak Tidak Ditemukan";
                break;
            }
            $idBarang = $checkItem['id'];
            $id_saldo = $checkItem['id_saldo'];

            $checkDetailSaldo = handleCheckDetailSaldo($detailsaldoClass, $idBarang, $row['tahunprod']);
            if (is_null($checkDetailSaldo)) {
                $results['success'] = false;
                $results['messages'] = "<strong>Error! </strong> Tahun Produksi Tidak Ditemukan";
                break;
            }
            $id_detailsaldo = $checkDetailSaldo['id_detailsaldo'];

            $update = handleUpdateKoreksiIdSaldo($uploadClass, $row['id'], $id_saldo, $id_detailsaldo);
            if (!$update['success']) {
                $results['success'] = false;
                $results['messages'] = "<strong>Error! </strong> Gagal Check Data Koreksi";
                break;
            }
            $results['success'] = true;
        }
    }

    return $results;
}

function handleUpdateKoreksiIdSaldo($uploadClass, $idtmp, $id_saldo, $id_detailsaldo)
{
    $result = $uploadClass->updateKoreksiIdSaldo($idtmp, $id_saldo, $id_detailsaldo);
    return $result;
}

function handleCheckItem($saldoClass, $barangClass, $kdbrg, $rak)
{
    $checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
    $monthSaldoLastDate = SUBSTR($checkSaldoLastDate, 5, -3);
    $yearSaldoLastDate = SUBSTR($checkSaldoLastDate, 0, -6);

    $checkItem = $barangClass->getByItemByRak($monthSaldoLastDate, $yearSaldoLastDate, $kdbrg, $rak);
    $result = $checkItem->fetch_assoc();
    return $result;
}

function handleCheckDetailSaldo($detailsaldoClass, $idBarang, $tahunprod)
{
    $checkDetailSaldo = $detailsaldoClass->getDetailSaldoByidAndYearProd($idBarang, $tahunprod);
    $result = $checkDetailSaldo->fetch_assoc();
    return $result;
}
