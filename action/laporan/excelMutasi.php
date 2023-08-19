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

if ( !empty($_GET) )
{

$tglAwalMTS  = $koneksi->real_escape_string($_GET['a']);
$tglAkhirMTS = $koneksi->real_escape_string($_GET['b']);
/*$tglAwalMTS  = '2018-03-01';
$tglAkhirMTS ='2018-03-20';*/
$tglSkrg     = date('d-m-Y');

//echo "Pada Bulan ini Terdapat".$hari1."hari";
//aray bulan
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

$excelku = new PHPExcel();

// Set properties
$excelku->getProperties()->setCreator("Ian Septiana")
                         ->setLastModifiedBy("Ian Septiana");


// isi


// Set lebar kolom
$excelku->getActiveSheet()->getColumnDimension('A')->setWidth(9);
$excelku->getActiveSheet()->getColumnDimension('B')->setWidth(11);
$excelku->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$excelku->getActiveSheet()->getColumnDimension('E')->setWidth(12);
$excelku->getActiveSheet()->getColumnDimension('F')->setWidth(12);
$excelku->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$excelku->getActiveSheet()->getColumnDimension('H')->setWidth(10);





// Mergecell, menyatukan beberapa kolom
$excelku->getActiveSheet()->mergeCells('A1:H1');
$excelku->getActiveSheet()->mergeCells('A2:H2');
$excelku->getActiveSheet()->mergeCells('A3:H3');
// $excelku->getActiveSheet()->mergeCells('A4:G4');
// $excelku->getActiveSheet()->mergeCells('B6:C6');
// $excelku->getActiveSheet()->mergeCells('D6:G6');


// Buat Kolom judul tabel
// Buat Kolom judul tabel
$SI = $excelku->setActiveSheetIndex(0);
$SI->setCellValue('A1', 'LAPORAN AKTIVITAS MUTASI BARANG'); //Judul laporan
$SI->setCellValue('A2', 'PT. KHARISMA UTAMA SENTOSA'); //Judul laporan
$SI->setCellValue('A3', 'PERIODE: ' . TanggalIndo($tglAwalMTS).' S/D '.TanggalIndo($tglAkhirMTS)); //Kolom bulanKartu tahunKartu
$SI->setCellValue('A4', 'NO'); //Kolom Barang
$SI->setCellValue('B4', 'TANGGAL'); //Kolom S.Awal
$SI->setCellValue('C4', 'NO MUTASI'); //Kolom S.Awal
$SI->setCellValue('D4', 'NAMA BARANG'); //Kolom S.Awal
$SI->setCellValue('E4', 'ASAL RAK'); //Kolom S.Awal
$SI->setCellValue('F4', 'LOKASI RAK'); //Kolom B.Masuk
$SI->setCellValue('G4', 'QTY'); //Kolom 
$SI->setCellValue('H4', 'TOTAL'); //Kolom 


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

// $excelku->getActiveSheet()->setSharedStyle($headerStylenya, "F3:AH3");

//Menggunakan HeaderStylenya
$excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:H4");
// $excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:AI4");

// $SI->setCellValue("A".$baris,$no); //mengisi data untuk nomor urut


//Membuat garis di body tabel (isi data)
// $excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A5:AI$baris");

  $query = "SELECT a.tgl, brg, asalRak, rak, suratJln, jml_msk, total, rowspan
      FROM (
      SELECT tgl, brg, detMsk.rak AS asalRak, rak.rak AS rak, suratJln, jml_msk
      FROM detail_masuk AS detMsk
      JOIN masuk AS msk USING(id_msk)
      JOIN detail_brg USING(id)
      JOIN barang USING(id_brg)
      JOIN rak USING(id_rak)
      WHERE retur = '3' AND msk.tgl BETWEEN '$tglAwalMTS' AND '$tglAkhirMTS' ORDER BY tgl ASC
      ) AS a
      LEFT JOIN (
      SELECT suratJln, COUNT(suratJln) AS rowspan, SUM(jml_msk) AS total
      FROM detail_masuk
      JOIN masuk AS msk1 USING(id_msk)
      WHERE retur= '3' AND msk1.tgl BETWEEN '$tglAwalMTS' AND '$tglAkhirMTS'
      GROUP BY suratJln
      ) AS b USING(suratJln) ORDER BY tgl DESC";

  $rest = $koneksi->query($query);

    
if ($rest->num_rows > 0)
{


$fetch = $rest->fetch_all(MYSQL_ASSOC);

//echo "<pre>". print_r($fetch); die;
foreach ($fetch as $key => $val) 
{
  $result[$val['suratJln']][] = $val;

}



$no=1;
$baris=5;
$margecell="";

foreach ($result as $kat => $array)
{

  foreach ($array as $index => $val)
  {
    


    if ($index==0)
    {

      $SI->setCellValue("A".$baris,$no); //mengisi data untuk nomor urut

    }

      $SI->setCellValue("B".$baris,TanggalIndo($val['tgl']));

    if ($index==0)
    {

      $SI->setCellValue("C".$baris,$val['suratJln']);

      // $excelku->getActiveSheet()->mergeCells("C". $baris .":C".$margecell);
      // $excelku->getActiveSheet()->mergeCells("D". $baris .":D".$margecell);
      // $excelku->getActiveSheet()->mergeCells("E". $baris .":E".$margecell);

      $no++;

    }

      $SI->setCellValue("D".$baris,$val['brg']); //mengisi data untuk nomor urut
      $SI->setCellValue("E".$baris,$val['asalRak']); //mengisi data untuk nomor urut
      $SI->setCellValue("F".$baris,$val['rak']); //mengisi data untuk nomor urut
      $SI->setCellValue("G".$baris,$val['jml_msk']); //mengisi data untuk nomor urut

    if ($index==0)
    {

      if ( $val['rowspan'] > 1 )
      {

        $margecell = ($baris+$val['rowspan'])-1;
        $excelku->getActiveSheet()->mergeCells("A". $baris .":A".$margecell);
        $excelku->getActiveSheet()->mergeCells("C". $baris .":C".$margecell);
        $excelku->getActiveSheet()->mergeCells("H". $baris .":H".$margecell);
        $margecell = "";
      }

      $SI->setCellValue("H".$baris,$val['total']);

    }

    
  $baris++;
  }
}


}
else
{
  $SI = $excelku->setActiveSheetIndex(0);
  $SI->setCellValue('A1', 'LAPORAN YANG ANDA MINTA TIDAK ADA');
  $baris = 5;
}

// isi
//Membuat garis di body tabel (isi data)
$excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A5:H$baris");

//Memberi nama sheet
$excelku->getActiveSheet()->setTitle('Laporan-Retur-'.$tglSkrg);

$excelku->setActiveSheetIndex(0);

// untuk excel 2007 atau yang berekstensi .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Laporan-Retur-'.$tglSkrg.'.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($excelku, 'Excel2007');
$objWriter->save('php://output');
exit;

}
else
{

  echo "Tidak Bisa Membuat Excel";

}

?>