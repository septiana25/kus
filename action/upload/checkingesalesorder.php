<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';

$soClass = new Salesorder($koneksi);

try {
    $resultCheckingSo = handleCheckingSalesOrder($soClass);

    if (!$resultCheckingSo['success']) {
        $valid['success'] = false;
        $valid['messages'] = $resultCheckingSo['messages'];
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


function handleCheckingSalesOrder($soClass)
{
    $result = $soClass->getDataSalesOrderUnprocessed();

    $results = [
        'success' => false,
        'messages' => []
    ];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_array()) {
            if (!is_null($row['nopol']) && !is_null($row['toko']) && !is_null($row['brg'])) {
                $update = $soClass->updateStatusSalesOrder($row['id_so'], '1');
                if (!$update['success']) {
                    $results['success'] = false;
                    $results['messages'] = "<strong>Error! </strong> Gagal Check Data Koreksi";
                    break;
                }
            } else {
                $results['success'] = false;
                $results['messages'] = "<strong>Error! </strong> Gagal Check Data Koreksi";
                break;
            }
            $results['success'] = true;
        }
    }

    return $results;
}
