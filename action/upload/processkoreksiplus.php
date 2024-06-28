<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/upload.php';
require_once '../class/barang.php';
require_once '../class/saldo.php';
require_once '../class/masuk.php';
require_once '../class/detailsaldo.php';

$uploadClass = new Upload($koneksi);
$saldoClass = new Saldo($koneksi);
$masukClass = new Masuk($koneksi);
$detailsaldoClass = new DetailSaldo($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());
$koneksi->begin_transaction();
$sql_success   = "";

try {
    $resultKoreksi = handleDataKoreksi($uploadClass, $saldoClass, $masukClass);
    if (!$resultKoreksi['success']) {
        $valid['success'] = false;
        $valid['messages'] = $resultKoreksi['messages'];
    } else {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Success! </strong>Data Selesai Diproses";
        $sql_success .= "success";
    }
} catch (\Throwable $th) {
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Data Ada Yang Gagal";
} finally {
    if ($sql_success) {
        $koneksi->commit();
    } else {
        $koneksi->rollback();
    }
    $koneksi->close();
    echo json_encode($valid);
}

function handleDataKoreksi($uploadClass, $saldoClass, $masukClass)
{
    $dataKoreksi = $uploadClass->getDataByIdSaldoNotNull($type = '2');
    $results = [
        'success' => true,
        'messages' => []
    ];

    if ($dataKoreksi->num_rows >= 1) {
        $prosesMasuk = handleInsertMasuk($masukClass);
        if (!$prosesMasuk['success']) {
            return [
                'success' => false,
                'messages' => "<strong>Error! </strong> Insert Masuk Gagal"
            ];
        }
        while ($row = $dataKoreksi->fetch_array()) {

            $dataSaldo = handleDataSaldo($saldoClass, $row['id_saldo']);
            $idBarang = $dataSaldo['id'];
            $saldoAwal = $dataSaldo['saldo_akhir'];


            $prosesDetailMasuk = handleInsertDetailMasuk($masukClass, $prosesMasuk['id'], $idBarang, $row['qty']);
            if (!$prosesDetailMasuk['success']) {
                $results['success'] = false;
                $results['messages'] = "<strong>Error! </strong> Insert Detail Masuk Gagal";
                break;
            }

            $prosesKoreksi = handleUpdateSaldoByKoreksi($saldoClass, $row['id_saldo'], $row['qty']);
            if (!$prosesKoreksi['success']) {
                $results['success'] = false;
                $results['messages'] = "<strong>Error! </strong> Update Saldo " . $row['brg'] . " Gagal";
                break;
            }

            $updateStatus = handleUpdateStatusKoreksi($uploadClass, $row['id'], $saldoAwal);
            if (!$updateStatus['success']) {
                $results['success'] = false;
                $results['errors'] = "<strong>Error! </strong> Update Status " . $row['brg'] . " Gagal";
                break;
            }
        }
    } else {
        $results['success'] = false;
        $results['messages'] = "<strong>Error! </strong> Data Koreksi Plus Tidak Bisa Diproses";
    }

    return $results;
}

function handleInsertMasuk($masukClass)
{
    $noPO = handleSuratJalanIncrement($masukClass);
    $result = $masukClass->save(date('Y-m-d'), $noPO, $_SESSION['nama']);
    return $result;
}

function handleInsertDetailMasuk($masukClass, $idMsk, $id, $jmlMsk)
{
    $jam = date('H:i:s');
    $ket = 'Koreksi Hasil SO';
    $result = $masukClass->saveDetail($idMsk, $id, $jam, $jmlMsk, $ket, '1');
    return $result;
}

function handleUpdateSaldoByKoreksi($saldoClass, $id_saldo, $qty)
{
    $result = $saldoClass->updateSaldoPlus($id_saldo, $qty);
    return $result;
}

function handleUpdateStatusKoreksi($uploadClass, $id, $saldoAwal)
{
    $atUpdate = date('Y-m-d H:i:s');
    $result = $uploadClass->updateStatusKoreksi($id, $saldoAwal, $atUpdate);
    return $result;
}

function handleDataSaldo($saldoClass, $id_saldo)
{
    $saldo = $saldoClass->getSaldoByIdSaldo($id_saldo);
    $saldoFetch = $saldo->fetch_assoc();
    $saldoFetch['saldo_akhir'];
    return $saldoFetch;
}

function handleSuratJalanIncrement($masukClass)
{
    $result = $masukClass->getLastDataKoreksiPlus();
    $resultFetch = $result->fetch_assoc();

    $noPO = $resultFetch['suratJln'];

    // Return default value if noPO is empty
    if (empty($noPO)) {
        return 'KP' . date("ym") . '-000001';
    }

    $deleteChar = substr($noPO, 7);
    $increment = $deleteChar + 1;

    // Use str_pad to ensure the serial number is always 6 digits
    $serial = str_pad($increment, 6, '0', STR_PAD_LEFT);

    $newNoPO = 'KP' . date("ym") . '-' . $serial;
    return $newNoPO;
}
