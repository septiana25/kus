<?php
/*******************************************
    Export Excel dengan PHPExcel
 
    Dibuat oleh : Danni Moring
    pemrograman : PHP
******************************************/

require_once '../../function/koneksi.php';
require_once '../../function/PHPExcel.php';
require_once '../../function/session.php';

//echo "Pada Bulan ini Terdapat".$hari1."hari";
//aray bulan
//$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

//date_default_timezone_set("Asia/Jakarta");

$excelku = new PHPExcel();

// Set properties
$excelku->getProperties()->setCreator("Ian Septiana")
                         ->setLastModifiedBy("Ian Septiana");

// Set lebar kolom
$excelku->getActiveSheet()->getColumnDimension('A')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$excelku->getActiveSheet()->getColumnDimension('F')->setWidth(15);


// Mergecell, menyatukan beberapa kolom
$excelku->getActiveSheet()->mergeCells('A1:F1');
$excelku->getActiveSheet()->mergeCells('A2:F2');
$excelku->getActiveSheet()->mergeCells('A3:A4');
$excelku->getActiveSheet()->mergeCells('B3:B4');
$excelku->getActiveSheet()->mergeCells('C3:C4');
$excelku->getActiveSheet()->mergeCells('D3:D4');
$excelku->getActiveSheet()->mergeCells('E3:E4');
$excelku->getActiveSheet()->mergeCells('F3:F4');

// Buat Kolom judul tabel
$SI = $excelku->setActiveSheetIndex(0);
$SI->setCellValue('A1', 'LAPORAN TANSAKSI GUDANG CV.KHARISMA TIARA ABADI'); //Judul laporan
$SI->setCellValue('A3', 'ID DETAIL KELUAR'); //Kolom rak
$SI->setCellValue('B3', 'ID KELUAR'); //Kolom Barang
$SI->setCellValue('C3', 'ID DETAIL BARANG'); //Kolom S.Awal
$SI->setCellValue('D3', 'JUMLAH KELUAR'); //Kolom B.Masuk
$SI->setCellValue('E4', 'TANGGAL'); //Kolom 
$SI->setCellValue('F4', 'JAM'); //Kolom 
//Mengeset Syle nya
$headerStylenya = new PHPExcel_Style();
$bodyStylenya   = new PHPExcel_Style();

$headerStylenya->applyFromArray(
  array('fill'  => array(
      'type'    => PHPExcel_Style_Fill::FILL_SOLID,
      'color'   => array('argb' => 'FFEEEEEE')),
      'borders' => array('bottom'=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
            'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'left'      => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
      )
  ));
  
$bodyStylenya->applyFromArray(
  array('fill'  => array(
      'type'  => PHPExcel_Style_Fill::FILL_SOLID,
      'color' => array('argb' => 'FFFFFFFF')),
      'borders' => array(
            'bottom'  => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'right'   => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'left'      => array('style' => PHPExcel_Style_Border::BORDER_THIN),
            'top'     => array('style' => PHPExcel_Style_Border::BORDER_THIN)
      )
    ));

//Menggunakan HeaderStylenya
$excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A3:F3");
$excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:F4");

// Mengambil data dari tabel
$strsql = "SELECT id_det_klr, id_klr, id, jml_klr, tgl, jam FROM keluar JOIN detail_keluar USING(id_klr)";
$res    = $koneksi->query($strsql);
$baris  = 5; //Ini untuk dimulai baris datanya, karena di baris 3 itu digunakan untuk header tabel
$no     = 1;

while ($row = $res->fetch_assoc()) {
  $SI->setCellValue("A".$baris,$row['id_det_klr']); //mengisi data untuk nomor urut
  $SI->setCellValue("B".$baris,$row['id_klr']); //mengisi data untuk nama
  $SI->setCellValue("C".$baris,$row['id']); //mengisi data untuk alamat
  $SI->setCellValue("D".$baris,$row['jml_klr']); //mengisi data untuk TELP
  $SI->setCellValue("E".$baris,$row['tgl']); //mengisi data untuk TELP
  $SI->setCellValue("F".$baris,$row['jam']); //mengisi data untuk TELP
  $baris++; //looping untuk barisnya
}
//Membuat garis di body tabel (isi data)
$excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A5:F$baris");

//Memberi nama sheet
$excelku->getActiveSheet()->setTitle('Laporan-Barang-Keluar');

$excelku->setActiveSheetIndex(0);

// untuk excel 2007 atau yang berekstensi .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Laporan-Barang-Keluar.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($excelku, 'Excel2007');
$objWriter->save('php://output');
exit;

?>