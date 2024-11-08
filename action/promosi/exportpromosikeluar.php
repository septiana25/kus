<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/promosi.php';
require_once '../../function/PHPExcel.php';

$promosiClass = new Promosi($koneksi);

function handlePrintPromosiKeluar($promosiClass)
{
    $result = $promosiClass->fetchAllPromosiKeluar();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function exportToExcel($data)
{
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->getProperties()->setCreator("Sistem")
        ->setLastModifiedBy("Sistem")
        ->setTitle("Laporan Promosi Keluar")
        ->setSubject("Laporan Promosi Keluar")
        ->setDescription("Laporan Promosi Keluar generated using PHP")
        ->setKeywords("office PHPExcel php")
        ->setCategory("Laporan Promosi");

    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();

    // Set judul
    $sheet->setCellValue('A1', 'LAPORAN PROMOSI KELUAR');
    $sheet->mergeCells('A1:G1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    // Set periode
    $row = 2;
    /* $sheet->setCellValue("A$row", 'Periode: ' . date('d-m-Y', strtotime($_GET['at_create'])) . ' s/d ' . date('d-m-Y', strtotime($_GET['at_create'])));
    $sheet->mergeCells("A$row:G$row"); */
    $row++;

    // Set header tabel
    $headers = ['No Transaksi', 'Divisi', 'Sales', 'Toko', 'Item', 'Qty', 'Tanggal'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . $row, $header);
        $sheet->getStyle($col . $row)->getFont()->setBold(true);
        $sheet->getStyle($col . $row)->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E0E0E0');
        $col++;
    }
    $row++;

    $totalQty = 0;
    foreach ($data as $item) {
        $sheet->setCellValue('A' . $row, $item['no_trank']);
        $sheet->setCellValue('B' . $row, $item['divisi']);
        $sheet->setCellValue('C' . $row, $item['sales']);
        $sheet->setCellValue('D' . $row, $item['toko']);
        $sheet->setCellValue('E' . $row, $item['item']);
        $sheet->setCellValue('F' . $row, $item['qty']);
        $sheet->setCellValue('G' . $row, date('d-m-Y', strtotime($item['at_create'])));
        $totalQty += $item['qty'];
        $row++;
    }

    // Tambahkan baris total
    $sheet->setCellValue('E' . $row, 'Total Qty');
    $sheet->setCellValue('F' . $row, $totalQty);
    $sheet->getStyle('E' . $row . ':F' . $row)->getFont()->setBold(true);
    $sheet->getStyle('E' . $row . ':F' . $row)->getFill()
        ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
        ->getStartColor()->setRGB('E0E0E0');

    // Atur lebar kolom
    $sheet->getColumnDimension('A')->setWidth(20); // No Transaksi
    $sheet->getColumnDimension('B')->setWidth(15); // Divisi
    $sheet->getColumnDimension('C')->setWidth(20); // Sales
    $sheet->getColumnDimension('D')->setWidth(30); // Toko
    $sheet->getColumnDimension('E')->setWidth(30); // Item
    $sheet->getColumnDimension('F')->setWidth(10); // Qty
    $sheet->getColumnDimension('G')->setWidth(15); // Tanggal

    // Buat file Excel
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $filename = 'laporan_promosi_keluar_' . date('YmdHis') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $objWriter->save('php://output');
}

// Proses ekspor
try {
    /* $tanggal_awal = trim($koneksi->real_escape_string($_GET['tanggal_awal']));
    $tanggal_akhir = trim($koneksi->real_escape_string($_GET['tanggal_akhir']));

    if (!isset($tanggal_awal) || empty($tanggal_awal) || !isset($tanggal_akhir) || empty($tanggal_akhir)) {
        throw new Exception('Tanggal tidak valid');
    } */

    $result = handlePrintPromosiKeluar($promosiClass);
    exportToExcel($result);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
}
