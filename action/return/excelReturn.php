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

$tglAwalRtr  = $koneksi->real_escape_string($_GET['a']);
$tglAkhirRtr = $koneksi->real_escape_string($_GET['b']);
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
$excelku->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$excelku->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$excelku->getActiveSheet()->getColumnDimension('F')->setWidth(30);
$excelku->getActiveSheet()->getColumnDimension('G')->setWidth(30);
$excelku->getActiveSheet()->getColumnDimension('H')->setWidth(12);
$excelku->getActiveSheet()->getColumnDimension('I')->setWidth(10);
$excelku->getActiveSheet()->getColumnDimension('J')->setWidth(10);





// Mergecell, menyatukan beberapa kolom
$excelku->getActiveSheet()->mergeCells('A1:J1');
$excelku->getActiveSheet()->mergeCells('A2:J2');
$excelku->getActiveSheet()->mergeCells('A3:J3');
// $excelku->getActiveSheet()->mergeCells('A4:G4');
// $excelku->getActiveSheet()->mergeCells('B6:C6');
// $excelku->getActiveSheet()->mergeCells('D6:G6');


// Buat Kolom judul tabel
// Buat Kolom judul tabel
$SI = $excelku->setActiveSheetIndex(0);
$SI->setCellValue('A1', 'LAPORAN AKTIVITAS RETUR BARANG'); //Judul laporan
$SI->setCellValue('A2', 'PT. KHARISMA UTAMA SENTOSA'); //Judul laporan
$SI->setCellValue('A3', 'PERIODE: ' . TanggalIndo($tglAwalRtr).' S/D '.TanggalIndo($tglAkhirRtr)); //Kolom bulanKartu tahunKartu
$SI->setCellValue('A4', 'NO'); //Kolom Barang
$SI->setCellValue('B4', 'TANGGAL'); //Kolom S.Awal
$SI->setCellValue('C4', 'NAMA TOKO'); //Kolom S.Awal
$SI->setCellValue('D4', 'NO RETUR'); //Kolom S.Awal
$SI->setCellValue('E4', 'DARI FAKTUR'); //Kolom S.Awal
$SI->setCellValue('F4', 'NAMA BARANG'); //Kolom B.Masuk
$SI->setCellValue('G4', 'KETERANGAN'); //Kolom 
$SI->setCellValue('H4', 'LOKASI'); //Kolom 
$SI->setCellValue('I4', 'QTY'); //Kolom 
$SI->setCellValue('J4', 'TOTAL'); //Kolom 


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
$excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:J4");
// $excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:AI4");

// $SI->setCellValue("A".$baris,$no); //mengisi data untuk nomor urut


//Membuat garis di body tabel (isi data)
// $excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A5:AI$baris");

  $query = "SELECT tgl_msk, toko, a.suratJln, no_faktur, brg, rak, jml_msk, total, ket, rowspan
        FROM (
        SELECT msk.tgl AS tgl_msk, IFNULL(toko, toko1) AS toko, suratJln, no_faktur, brg, rak.rak AS rak, jml_msk,
        detMsk.ket AS ket
        FROM detail_masuk AS detMsk
        JOIN masuk AS msk USING(id_msk)
        JOIN detail_brg AS detBrg USING(id)
        JOIN barang USING(id_brg)
        JOIN rak USING(id_rak)
        LEFT JOIN keluar AS klr USING(no_faktur)
        LEFT JOIN toko ON klr.id_toko=toko.id_toko
        WHERE retur= '1' AND msk.tgl BETWEEN '$tglAwalRtr' AND '$tglAkhirRtr' ORDER BY msk.tgl ASC
        ) AS a
        LEFT JOIN (
        SELECT suratJln, COUNT(suratJln) AS rowspan, SUM(jml_msk) AS total
        FROM detail_masuk
        JOIN masuk USING(id_msk)
        WHERE retur= '1'
        GROUP BY suratJln
        ) AS b USING(suratJln) ORDER BY tgl_msk DESC";

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

      $SI->setCellValue("B".$baris,TanggalIndo($val['tgl_msk']));

    if ($index==0)
    {

      $SI->setCellValue("C".$baris,$val['toko']);
      $SI->setCellValue("D".$baris,$val['suratJln']);
      $SI->setCellValue("E".$baris,$val['no_faktur']);

      // $excelku->getActiveSheet()->mergeCells("C". $baris .":C".$margecell);
      // $excelku->getActiveSheet()->mergeCells("D". $baris .":D".$margecell);
      // $excelku->getActiveSheet()->mergeCells("E". $baris .":E".$margecell);

      $no++;

    }

      $SI->setCellValue("F".$baris,$val['brg']); //mengisi data untuk nomor urut
      $SI->setCellValue("G".$baris,$val['ket']); //mengisi data untuk nomor urut
      $SI->setCellValue("H".$baris,$val['rak']); //mengisi data untuk nomor urut
      $SI->setCellValue("I".$baris,$val['jml_msk']); //mengisi data untuk nomor urut

    if ($index==0)
    {

      if ( $val['rowspan'] > 1 )
      {

        $margecell = ($baris+$val['rowspan'])-1;
        $excelku->getActiveSheet()->mergeCells("A". $baris .":A".$margecell);
        $excelku->getActiveSheet()->mergeCells("C". $baris .":C".$margecell);
        $excelku->getActiveSheet()->mergeCells("D". $baris .":D".$margecell);
        $excelku->getActiveSheet()->mergeCells("E". $baris .":E".$margecell);
        $excelku->getActiveSheet()->mergeCells("J". $baris .":J".$margecell);
        $margecell = "";
      }

      $SI->setCellValue("J".$baris,$val['total']);

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
$excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A5:J$baris");

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