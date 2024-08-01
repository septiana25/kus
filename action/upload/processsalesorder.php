<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/detailsaldo.php';
require_once '../class/salesorder.php';
require_once '../class/barang.php';
require_once '../class/saldo.php';

$saldoClass = new Saldo($koneksi);
$soClass = new Salesorder($koneksi);
$detailsaldoClass = new DetailSaldo($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

$conn = $koneksi;

try {
    $resultKoreksi = handleProcessSO($soClass, $saldoClass, $detailsaldoClass, $conn);
    if (!$resultKoreksi['success']) {
        $valid['success'] = false;
        $valid['messages'] = $resultKoreksi['messages'];
    } else {
        $valid['success'] = true;
        $valid['messages'] = $resultKoreksi['messages'];
    }
} catch (\Throwable $th) {
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Data Ada Yang Gagal";
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleProcessSO($soClass, $saldoClass, $detailsaldoClass, $conn)
{
    $results = [
        'success' => true,
        'messages' => []
    ];

    $dataSO = $soClass->getDataSalesOrderByStatus('1');
    $tmpDataSO = [];

    while ($rowSO = $dataSO->fetch_assoc()) {
        $tmpDataSO[] = $rowSO;
    }

    if ($dataSO->num_rows == 0) {
        $results['success'] = false;
        $results['messages'][] = "Tidak ada data";
        return $results;
    }

    $groupedSaldo = handleFilterSO($saldoClass, $tmpDataSO);

    foreach ($tmpDataSO as $rowSO) {
        $kdbrg = $rowSO['kdbrg'];
        $brg = substr($rowSO['brg'], 0, 10);
        $qtySO = $rowSO['qty'];
        $statusUpdate = [
            'success' => false
        ];

        // Cari kdbrg yang sesuai di $groupedSaldo
        $saldoItem = array_filter($groupedSaldo, function ($item) use ($kdbrg) {
            return $item['kdbrg'] === $kdbrg;
        });

        if (empty($saldoItem)) {
            $results['success'] = false;
            $results['messages'][] = "Tidak ada stok untuk {$brg}";
            continue;
        }

        $saldoItem = reset($saldoItem); // Ambil item pertama (dan satu-satunya) dari hasil filter

        $remainingQty = $qtySO;
        foreach ($saldoItem['details'] as $detail) {
            if ($remainingQty <= 0) break;

            $conn->begin_transaction();

            if ($detail['jumlah'] <= 0) {
                $results['success'] = false;
                $results['messages'][] = "Stok {$brg} di rak {$detail['rak']} habis";
                continue;
            }
            $qtyToDeduct = min($remainingQty, $detail['jumlah']); //tentukan jumlah yang akan dikurangi dari saldo agar tidak melebihi sisa stok
            $remainingQty -= $qtyToDeduct; //jumlah yang tersisa setelah dikurangi
            // Simpan perubahan ke database
            $updateDetailSaldo = $detailsaldoClass->updateMinus($detail['id_detailsaldo'], $qtyToDeduct);
            if (!$updateDetailSaldo) {
                $conn->rollback();
                $results['success'] = false;
                $results['messages'][] = "Gagal memperbarui stok untuk {$brg} di rak {$detail['rak']} {$updateDetailSaldo['message']}";
                continue; // Lanjut ke detail berikutnya
            }

            $updateSaldo = $saldoClass->updateSaldoMinus($detail['id_saldo'], $qtyToDeduct);
            if (!$updateSaldo['success']) {
                $conn->rollback();
                $results['success'] = false;
                $results['messages'][] = "Gagal memperbarui stok untuk {$brg} di rak {$detail['rak']} {$updateSaldo['message']}";
                continue; // Lanjut ke detail berikutnya
            }

            $updateSisaSO = $soClass->updateSisaSalesOrder($rowSO['id_so'], $remainingQty);
            if (!$updateSisaSO['success']) {
                $conn->rollback();
                $results['success'] = false;
                $results['messages'][] = "Gagal memperbarui sisa stok untuk {$brg} di rak {$detail['rak']} {$updateSisaSO['message']}";
                continue; // Lanjut ke detail berikutnya
            }

            $insertProssesSO = [
                'id_so' => $rowSO['id_so'],
                'id_detailsaldo' => $detail['id_detailsaldo'],
                'qty_pro' => $qtyToDeduct
            ];
            $insertProssesSO = $soClass->insertProssesSalesOrder($insertProssesSO);
            if (!$insertProssesSO['success']) {
                $conn->rollback();
                $results['success'] = false;
                $results['messages'][] = "Gagal memasukan data ke proses {$insertProssesSO['message']}";
                continue; // Lanjut ke detail berikutnya
            }

            $conn->commit(); // Commit jika semua operasi berhasil
            $statusUpdate['success'] = true;
            $results['messages'][] = "Berhasil Di Proses";
        }

        if ($remainingQty > 0) {
            $results['messages'][] = "Stok tidak cukup untuk {$brg}, kurang {$remainingQty} unit";
        }

        if ($remainingQty == 0 && $statusUpdate['success']) {
            $atUpdate = date('Y-m-d H:i:s');
            $updateSOResult = $soClass->updateDateUpdateSalesOrder($rowSO['id_so'], $atUpdate);
            if (!$updateSOResult['success']) {
                $results['success'] = false;
                $results['messages'][] = "Gagal memperbarui status SO untuk {$brg}";
            }
        }
    }
    return $results;
}

/*
 * Fungsi untuk mengambil data sales order dengan status 1
 * @param 0 =belum di cek, 1=sudah di cek
 * @return array
 */
function handleFilterSO($saldoClass, $dataSO)
{
    $kdbrg = array_unique(array_column($dataSO, 'kdbrg'));

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
                'id_saldo' => $row['id_saldo'],
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
            'id_saldo' => $item['id_saldo'],
            'rak' => $item['rak'],
            'tahunprod' => $item['tahunprod'],
            'tglprod' => $item['tglprod'],
            'jumlah' => $item['jumlah']
        ];
    }

    return array_values($groupedData);
}
