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
    $results = ['success' => true, 'messages' => []];

    if ($result->num_rows == 0) {
        $results['messages'] = "Tidak ada data yang perlu diproses";
        return $results;
    }

    while ($row = $result->fetch_array()) {
        if ($row['status'] != '0') {
            continue;
        }

        if (is_null($row['nopol']) || is_null($row['toko']) || is_null($row['brg'])) {
            $results['success'] = false;
            $results['messages'] = "<strong>Error!</strong> Data Tidak Lengkap untuk ID: " . $row['id_so'];
            continue;
        }

        $update = $soClass->updateStatusSalesOrder($row['id_so'], '1');
        if (!$update['success']) {
            $results['success'] = false;
            $results['messages'] = "<strong>Error!</strong> Gagal Check Data untuk ID: " . $row['id_so'];
        } else {
            $results['messages'] = "Berhasil update status untuk ID: " . $row['id_so'];
        }
    }

    if ($results['success'] && empty($results['messages'])) {
        $results['messages'] = "Semua data berhasil diproses";
    }

    return $results;
}
