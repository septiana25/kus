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

//$kal   = CAL_GREGORIAN;
$bulan = date("m");
$tahun = date("Y");
$tgl = date("Y-m-d");
//$hari  = cal_days_in_month($kal, $bulan, $tahun);

//echo "Pada Bulan ini Terdapat".$hari1."hari";
//aray bulan
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

$excelku = new PHPExcel();

// Set properties
$excelku->getProperties()->setCreator("Ian Septiana")
                         ->setLastModifiedBy("Ian Septiana");
$Select = "SELECT kat, b.id_brg, brg, btsLimit, total,
         CASE 
          WHEN total <= btsLimit
            THEN 'KURANG'
          WHEN total > btsLimit
            THEN 'LEBIH'
            ELSE 'SET LIMIT TIDAK ADA'
         END AS stus_limit
     FROM(
       SELECT kat, id_brg, brg, tgl, SUM(saldo_akhir) AS total FROM saldo
         JOIN detail_brg USING(id)
         JOIN barang USING(id_brg)
         JOIN kat USING(id_kat)
       WHERE MONTH(tgl)=$bulan  AND YEAR(tgl)=$tahun
       GROUP BY id_brg
     )b
     LEFT JOIN(
         SELECT id_brg, btsLimit FROM tblLimit
     )c ON b.id_brg=c.id_brg";
// Set lebar kolom
$excelku->getActiveSheet()->getColumnDimension('A')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('B')->setWidth(50);
$excelku->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('D')->setWidth(11);
$excelku->getActiveSheet()->getColumnDimension('E')->setWidth(10);
$excelku->getActiveSheet()->getColumnDimension('F')->setWidth(20);

// Mergecell, menyatukan beberapa kolom
$excelku->getActiveSheet()->mergeCells('A1:F1');
$excelku->getActiveSheet()->mergeCells('A2:F2');

$SI = $excelku->setActiveSheetIndex(0);
$SI->setCellValue('A1', 'LAPORAN BAPER STOCK GUDANG PT.KHARISMA UTAMA SENTOSA'); //Judul laporan
$SI->setCellValue('A2', TanggalHuruf($tgl)); //Kolom bulan tahun
$SI->setCellValue('A4', 'NO'); //Kolom rak
$SI->setCellValue('B4', 'NAMA BARANG'); //Kolom Barang
$SI->setCellValue('C4', 'KATEGORI'); //Kolom Kategori
$SI->setCellValue('D4', 'SET LIMIT'); //Kolom Limit
$SI->setCellValue('E4', 'SALDO'); //Kolom Saldo
$SI->setCellValue('F4', 'KETERANGAN'); //Kolom KETERANGAN

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
$excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:F4");

//membuat isi tabel
$res    = $koneksi->query($Select);
$baris  = 5; //Ini untuk dimulai baris datanya, karena di baris 3 itu digunakan untuk header tabel
$no     = 1;

while ($row = $res->fetch_assoc())
{
  $SI->setCellValue("A".$baris,$no); //mengisi data untuk nomor urut
  $SI->setCellValue("B".$baris,utf8_encode($row['brg'])); //mengisi data untuk nama
  $SI->setCellValue("C".$baris,$row['kat']); //mengisi kategori
  $SI->setCellValue("D".$baris,$row['btsLimit']); //mengisi batas limit
  $SI->setCellValue("E".$baris,$row['total']); //mengisi saldo
  $SI->setCellValue("F".$baris,$row['stus_limit']); //mengisi keterangan limit

  $baris++; //looping untuk barisnya
  $no++;
}

//Membuat garis di body tabel (isi data)
$excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A5:F$baris");

//Memberi nama sheet
$excelku->getActiveSheet()->setTitle('Laporan-Baper-Stock-'.TanggalIndo($tgl));

$excelku->setActiveSheetIndex(0);

// untuk excel 2007 atau yang berekstensi .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Laporan-Baper-Stock-'.TanggalIndo($tgl).'.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($excelku, 'Excel2007');
$objWriter->save('php://output');
exit;

?>