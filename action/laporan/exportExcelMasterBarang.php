<?php
/*******************************************
    Export Excel dengan PHPExcel
 
    Dibuat oleh : Danni Moring
    pemrograman : PHP
******************************************/

require_once '../../function/koneksi.php';
require_once '../../function/PHPExcel.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';

$tgl   = date('Y-m-d');
$bulan = $_GET['b'];
$tahun = $_GET['t'];

//echo "Pada Bulan ini Terdapat".$hari1."hari";
//aray bulan


$excelku = new PHPExcel();

// Set properties
$excelku->getProperties()->setCreator("Ian Septiana")
                         ->setLastModifiedBy("Ian Septiana");

// Set lebar kolom
$excelku->getActiveSheet()->getColumnDimension('A')->setWidth(13);
$excelku->getActiveSheet()->getColumnDimension('B')->setWidth(13);
$excelku->getActiveSheet()->getColumnDimension('C')->setWidth(13);
$excelku->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$excelku->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('F')->setWidth(13);
$excelku->getActiveSheet()->getColumnDimension('G')->setWidth(13);


// Mergecell, menyatukan beberapa kolom
$excelku->getActiveSheet()->mergeCells('A1:G1');
$excelku->getActiveSheet()->mergeCells('A2:G2');

// Buat Kolom judul tabel
$SI = $excelku->setActiveSheetIndex(0);
$SI->setCellValue('A1', 'LAPORAN MASTER BARANG GUDANG PT.KHARISMA UTAMA SENTOSA'); //Judul laporan
$SI->setCellValue('A2', 'BULAN SALDO BARANG : '.$bulan.'/'.$tahun); //Kolom bulan tahun
$SI->setCellValue('A3', 'TANGGAL CETAK : '.TanggalHuruf($tgl)); //Kolom bulan tahun
$SI->setCellValue('A5', 'NO URUT'); //Kolom rak
$SI->setCellValue('B5', 'KODE BARANG'); //Kolom rak
$SI->setCellValue('C5', 'LOKASI RAK'); //Kolom rak
$SI->setCellValue('D5', 'NAMA BARANG'); //Kolom Barang
$SI->setCellValue('E5', 'KATEGORI'); //Kolom S.Awal
$SI->setCellValue('F5', 'SALDO AWAL'); //Kolom S.Awal
$SI->setCellValue('G5', 'SALDO AKHIR'); //Kolom B.Masuk

//Mengeset Syle nya
$headerStylenya = new PHPExcel_Style();
$bodyStylenya   = new PHPExcel_Style();

$headerStylenya->applyFromArray(
  array('fill'  => array(
      'type'    => PHPExcel_Style_Fill::FILL_SOLID,
      'color'   => array('argb' => 'FFEEEEEE')),
      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
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
$excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A5:G5");

// Mengambil data dari tabel
$strsql = "SELECT d.id_brg AS id_brg, nourt, kdbrg, d.rak, b.brg, saldo_awal, saldo_akhir, kat
           FROM(
           SELECT id_brg, rak, saldo_awal, saldo_akhir, tgl
           FROM detail_brg
           LEFT JOIN rak USING(id_rak)
           LEFT JOIN saldo USING(id)
           WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun
           )d
           RIGHT JOIN (
           SELECT id_brg, nourt, kdbrg, brg, kat
           FROM barang
           JOIN kat USING(id_kat)
           )b ON b.id_brg=d.id_brg ORDER BY rak, b.brg ASC";

$res         = $koneksi->query($strsql);
$baris       = 6; //Ini untuk dimulai baris datanya, karena di baris 3 itu digunakan untuk header tabel
$no          = 1;
$saldo_awal  = "";
$saldo_akhir = "";

while ($row = $res->fetch_assoc()) {
  $SI->setCellValue("A".$baris,$row['nourt']); //mengisi data untuk nomor urut
  $SI->setCellValue("B".$baris,$row['kdbrg']); //mengisi data untuk nomor urut
  $SI->setCellValue("C".$baris,$row['rak']); //mengisi data untuk nomor urut
  $SI->setCellValue("D".$baris,utf8_encode($row['brg'])); //mengisi data untuk nama
  $SI->setCellValue("E".$baris,$row['kat']); //mengisi data untuk alamat
  $SI->setCellValue("F".$baris,$row['saldo_awal']); //mengisi data untuk alamat
  $SI->setCellValue("G".$baris,$row['saldo_akhir']); //mengisi data untuk TELP

  $baris++; //looping untuk barisnya
  $no++;
  $saldo_awal  += $row['saldo_awal'];
  $saldo_akhir += $row['saldo_akhir'];

}

$SI->setCellValue("F".$baris,$saldo_awal); //mengisi data untuk alamat
$SI->setCellValue("G".$baris,$saldo_akhir); //mengisi data untuk TELP

//Membuat garis di body tabel (isi data)
$excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A6:G$baris");



//Memberi nama sheet
$excelku->getActiveSheet()->setTitle('Lap-BRG-'.$bulan.'-'.$tahun.'-'.TanggalIndo($tgl));

$excelku->setActiveSheetIndex(0);

// untuk excel 2007 atau yang berekstensi .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Laporan-Barang-'.$bulan.'-'.$tahun.'-'.TanggalIndo($tgl).'.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($excelku, 'Excel2007');
$objWriter->save('php://output');
exit;

?>