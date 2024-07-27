<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';
require_once '../class/barang.php';
require_once '../class/saldo.php';

$barangClass = new Barang($koneksi);
$saldoClass = new Saldo($koneksi);
$soClass = new Salesorder($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());
$koneksi->begin_transaction();
$sql_success   = "";

try {
    $resultKoreksi = handleProcessSO($soClass, $saldoClass, $barangClass);
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

function handleProcessSO($soClass, $saldoClass)
{
    $dataSO = $soClass->getDataSalesOrderByStatus('1');
    $groupedSaldo = handleFilterSO($saldoClass, $dataSO);
    var_dump($groupedSaldo);
    die;
}

/*
 * Fungsi untuk mengambil data sales order dengan status 1
 * @param 0 =belum di cek, 1=sudah di cek
 * @return array
 */
function handleFilterSO($saldoClass, $dataSO)
{
    $kdbrg = [];
    while ($row = $dataSO->fetch_assoc()) {
        $kdbrg[] = $row['kdbrg'];
    }

    $kdbrg = array_unique($kdbrg); //remove duplicate
    $kdbrg = array_values($kdbrg); //reset index

    $checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
    $monthSaldoLastDate = SUBSTR($checkSaldoLastDate, 5, -3);
    $yearSaldoLastDate = SUBSTR($checkSaldoLastDate, 0, -6);

    $getSaldo = $saldoClass->getSaldoByAnyKodeBarang($kdbrg, $monthSaldoLastDate, $yearSaldoLastDate);
    $saldoData = [];

    while ($row = $getSaldo->fetch_assoc()) {
        $year = substr($row['tahunprod'], 2, 4);
        $week = substr($row['tahunprod'], 0, 2);
        $start = date("Y-m-d", strtotime("01 Jan 20" . $year . " 00:00:00 GMT + " . $week . " weeks"));

        // Hanya tambahkan data jika tahun produksi lebih dari 2022
        if (substr($start, 0, 4) > '2022') {
            $saldoData[] = [
                'kdbrg' => $row['kdbrg'],
                'brg' => $row['brg'],
                'tahunprod' => $row['tahunprod'],
                'tglprod' => $start,
                'jumlah' => $row['jumlah'],
                'rak' => $row['rak'],
                'id_detailsaldo' => $row['id_detailsaldo'],
                'id' => $row['id'],
            ];
        }
    }
    usort($saldoData, function ($a, $b) {
        return strtotime($a['tglprod']) - strtotime($b['tglprod']);
    });

    $groupedData = groupSaldoByKdbrg($saldoData);

    return $groupedData;
}

/**
 * Fungsi untuk mengelompokan data saldo berdasarkan kode barang
 * @param array $saldoData
 * @return array
 */
function groupSaldoByKdbrg($saldoData)
{
    $groupedData = [];

    foreach ($saldoData as $item) {
        $kdbrg = $item['kdbrg'];
        if (!isset($groupedData[$kdbrg])) {
            $groupedData[$kdbrg] = [
                'kdbrg' => $kdbrg,
                'brg' => $item['brg'],
                'details' => []
            ];
        }
        $groupedData[$kdbrg]['details'][] = [
            'id_detailsaldo' => $item['id_detailsaldo'],
            'id' => $item['id'],
            'rak' => $item['rak'],
            'tahunprod' => $item['tahunprod'],
            'tglprod' => $item['tglprod'],
            'jumlah' => $item['jumlah']
        ];
    }

    return array_values($groupedData);
}
