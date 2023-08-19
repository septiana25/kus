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

$bulan = $koneksi->real_escape_string($_GET['b']);
$tahun = $koneksi->real_escape_string($_GET['t']);
// $bulan  = 03;
// $tahun = 2018;
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
$excelku->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$excelku->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excelku->getActiveSheet()->getColumnDimension('D')->setWidth(12);
$excelku->getActiveSheet()->getColumnDimension('E')->setWidth(30);
$excelku->getActiveSheet()->getColumnDimension('F')->setWidth(11);
$excelku->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$excelku->getActiveSheet()->getColumnDimension('H')->setWidth(10);
$excelku->getActiveSheet()->getColumnDimension('I')->setWidth(15);





// Mergecell, menyatukan beberapa kolom
$excelku->getActiveSheet()->mergeCells('A1:I1');
$excelku->getActiveSheet()->mergeCells('A2:I2');
$excelku->getActiveSheet()->mergeCells('A3:I3');
// $excelku->getActiveSheet()->mergeCells('A4:G4');
// $excelku->getActiveSheet()->mergeCells('B6:C6');
// $excelku->getActiveSheet()->mergeCells('D6:G6');


// Buat Kolom judul tabel
// Buat Kolom judul tabel
$SI = $excelku->setActiveSheetIndex(0);
$SI->setCellValue('A1', 'LAPORAN RINCI TANSAKSI KELUAR BARANG'); //Judul laporan
$SI->setCellValue('A2', 'PT. KHARISMA UTAMA SENTOSA'); //Judul laporan
$SI->setCellValue('A3', 'PERIODE: ' . $BulanIndo[(int)$bulan-1].' '.$tahun); //Kolom bulanKartu tahunKartu
$SI->setCellValue('A4', 'NO'); //Kolom Barang
$SI->setCellValue('B4', 'NO FAKTUR'); //Kolom S.Awal
$SI->setCellValue('C4', 'TOKO'); //Kolom S.Awal
$SI->setCellValue('D4', 'LOKASI RAK'); //Kolom S.Awal
$SI->setCellValue('E4', 'NAMA BARANG'); //Kolom S.Awal
$SI->setCellValue('F4', 'TANGGAL'); //Kolom B.Masuk
$SI->setCellValue('G4', 'QTY'); //Kolom B.Masuk
$SI->setCellValue('H4', 'TOTAL'); //Kolom 
$SI->setCellValue('I4', 'KETERANGAN'); //Kolom 



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
$excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:I4");
// $excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:AI4");

// $SI->setCellValue("A".$baris,$no); //mengisi data untuk nomor urut


//Membuat garis di body tabel (isi data)
// $excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A5:AI$baris");

  $query = "SELECT no_faktur, toko, rak, brg, tgl, jml_klr, total, ket, rowspan
          FROM(
          SELECT no_faktur, toko, rak, brg, tgl, jml_klr, ket
                FROM keluar
                RIGHT JOIN detail_keluar USING(id_klr)
                LEFT JOIN detail_brg USING(id)
                LEFT JOIN barang USING(id_brg)
                LEFT JOIN rak USING(id_rak)
                LEFT JOIN toko USING(id_toko)
                WHERE MONTH(tgl)=$bulan  AND YEAR(tgl)=$tahun
                ORDER BY tgl ASC
          ) AS a
          LEFT JOIN (
                SELECT no_faktur, COUNT(no_faktur) AS rowspan, SUM(jml_klr) AS total
                FROM detail_keluar
                JOIN keluar AS klr1 USING(id_klr)
                WHERE MONTH(tgl)=$bulan  AND YEAR(tgl)=$tahun
                GROUP BY no_faktur
          ) AS b USING(no_faktur) ORDER BY tgl ASC";

  $rest = $koneksi->query($query);

    
if ($rest->num_rows > 0)
{


$fetch = $rest->fetch_all(MYSQL_ASSOC);

//echo "<pre>". print_r($fetch); die;
foreach ($fetch as $key => $val) 
{
  $result[$val['no_faktur']][] = $val;

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
      $SI->setCellValue("B".$baris,$val['no_faktur']);
      $SI->setCellValue("C".$baris,$val['toko']);

    }

      $SI->setCellValue("D".$baris,$val['rak']);
      $SI->setCellValue("E".$baris,$val['brg']);

    if ($index==0)
    {

      $SI->setCellValue("F".$baris,TanggalIndo($val['tgl']));

      // $excelku->getActiveSheet()->mergeCells("C". $baris .":C".$margecell);
      // $excelku->getActiveSheet()->mergeCells("D". $baris .":D".$margecell);
      // $excelku->getActiveSheet()->mergeCells("E". $baris .":E".$margecell);

      $no++;

    }

      $SI->setCellValue("G".$baris,$val['jml_klr']); //mengisi data untuk nomor urut

    if ($index==0)
    {

      if ( $val['rowspan'] > 1 )
      {

        $margecell = ($baris+$val['rowspan'])-1;
        $excelku->getActiveSheet()->mergeCells("A". $baris .":A".$margecell);
        $excelku->getActiveSheet()->mergeCells("B". $baris .":B".$margecell);
        $excelku->getActiveSheet()->mergeCells("C". $baris .":C".$margecell);
        $excelku->getActiveSheet()->mergeCells("F". $baris .":F".$margecell);
        $excelku->getActiveSheet()->mergeCells("H". $baris .":H".$margecell);
        $margecell = "";
      }

      $SI->setCellValue("H".$baris,$val['total']);

    }

    $SI->setCellValue("I".$baris,$val['ket']);

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
$excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A5:I$baris");

//Memberi nama sheet
$excelku->getActiveSheet()->setTitle('Laporan-TransKlrRnci-'.$bulan.'-'.$tahun);

$excelku->setActiveSheetIndex(0);

// untuk excel 2007 atau yang berekstensi .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Laporan-TransKlrRnci-'.$bulan.'-'.$tahun.'.xlsx');
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