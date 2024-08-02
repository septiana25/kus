<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';
require_once '../../function/PHPExcel.php';

$soClass = new Salesorder($koneksi);

function compareRak($a, $b)
{
    preg_match('/([A-Z])(\d+)\.(\d+)/', $a, $matchesA);
    preg_match('/([A-Z])(\d+)\.(\d+)/', $b, $matchesB);

    if ($matchesA[1] != $matchesB[1]) {
        return strcmp($matchesA[1], $matchesB[1]);
    }

    if (intval($matchesA[2]) != intval($matchesB[2])) {
        return intval($matchesA[2]) - intval($matchesB[2]);
    }

    return intval($matchesA[3]) - intval($matchesB[3]);
}

function groupSaldoByKdbrg($data)
{
    $groupedData = array();

    while ($row = $data->fetch_assoc()) {
        $nopol = $row['nopol'];
        if (!isset($groupedData[$nopol])) {
            $groupedData[$nopol] = array(
                'nopol' => $nopol,
                'supir' => $row['supir'],
                'jenis' => $row['jenis'],
                'details' => array()
            );
        }
        $groupedData[$nopol]['details'][] = array(
            'toko' => $row['toko'],
            'brg' => $row['brg'],
            'rak' => $row['rak'],
            'tahunprod' => $row['tahunprod'],
            'qty_pro' => $row['qty_pro']
        );
    }

    foreach ($groupedData as &$group) {
        usort($group['details'], function ($a, $b) {
            return compareRak($a['rak'], $b['rak']);
        });
    }

    return array_values($groupedData);
}

function handlePrintSalesOrder($soClass, $nopol)
{
    $result = $soClass->getDataDetailProsessSalesOrder($nopol);
    return groupSaldoByKdbrg($result);
}

function exportToExcel($data)
{
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Sistem")
        ->setLastModifiedBy("Sistem")
        ->setTitle("Sales Order Gudang")
        ->setSubject("Sales Order Gudang")
        ->setDescription("Sales Order Gudang generated using PHP")
        ->setKeywords("office PHPExcel php")
        ->setCategory("Sales Order");

    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();

    // Set judul
    $sheet->setCellValue('A1', 'SALES ORDER GUDANG');
    $sheet->mergeCells('A1:E1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $row = 2;
    foreach ($data as $group) {
        // Set informasi header
        $sheet->setCellValue("A$row", $group['jenis'] . ' - ' . $group['nopol'] . ' - ' . $group['supir']);
        $sheet->mergeCells("A$row:E$row");
        $sheet->getStyle("A$row")->getFont()->setBold(true);
        $row++;

        $sheet->setCellValue("A$row", date('d-m-Y'));
        $sheet->mergeCells("A$row:E$row");
        $row++;

        // Set header tabel
        $sheet->setCellValue("A$row", 'Toko');
        $sheet->setCellValue("B$row", 'Barang');
        $sheet->setCellValue("C$row", 'Rak');
        $sheet->setCellValue("D$row", 'Tahun');
        $sheet->setCellValue("E$row", 'Qty');
        $sheet->getStyle("A$row:E$row")->getFont()->setBold(true);
        $sheet->getStyle("A$row:E$row")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E0E0E0');
        $row++;

        // Isi data
        foreach ($group['details'] as $detail) {
            $sheet->setCellValue("A$row", substr($detail['toko'], 0, 25));
            $sheet->setCellValue("B$row", substr($detail['brg'], 0, 46));
            $sheet->setCellValue("C$row", $detail['rak']);
            $sheet->setCellValue("D$row", $detail['tahunprod']);
            $sheet->setCellValue("E$row", $detail['qty_pro']);
            $row++;
        }

        // Tambahkan baris kosong setelah setiap grup
        $row += 2;
    }

    // Atur lebar kolom
    $sheet->getColumnDimension('A')->setWidth(30);
    $sheet->getColumnDimension('B')->setWidth(40);
    $sheet->getColumnDimension('C')->setWidth(10);
    $sheet->getColumnDimension('D')->setWidth(10);
    $sheet->getColumnDimension('E')->setWidth(10);

    // Buat file Excel
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename = 'sales_order_' . $data[0]['supir'] . '_' . date('YmdHis') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
}

// Proses ekspor
try {
    $nopol = trim($koneksi->real_escape_string($_GET['nopol']));
    if (!isset($nopol) || empty($nopol)) {
        throw new Exception('Nomor polisi tidak valid');
    }

    $result = handlePrintSalesOrder($soClass, $nopol);
    exportToExcel($result);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
}
