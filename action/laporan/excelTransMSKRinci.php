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
$excelku->getActiveSheet()->getColumnDimension('B')->setWidth(12);
$excelku->getActiveSheet()->getColumnDimension('C')->setWidth(30);
$excelku->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('E')->setWidth(11);
$excelku->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$excelku->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$excelku->getActiveSheet()->getColumnDimension('H')->setWidth(15);





// Mergecell, menyatukan beberapa kolom
$excelku->getActiveSheet()->mergeCells('A1:G1');
$excelku->getActiveSheet()->mergeCells('A2:G2');
$excelku->getActiveSheet()->mergeCells('A3:G3');
// $excelku->getActiveSheet()->mergeCells('A4:G4');
// $excelku->getActiveSheet()->mergeCells('B6:C6');
// $excelku->getActiveSheet()->mergeCells('D6:G6');


// Buat Kolom judul tabel
// Buat Kolom judul tabel
$SI = $excelku->setActiveSheetIndex(0);
$SI->setCellValue('A1', 'LAPORAN RINCI TANSAKSI MASUK BARANG'); //Judul laporan
$SI->setCellValue('A2', 'PT. KHARISMA UTAMA SENTOSA'); //Judul laporan
$SI->setCellValue('A3', 'PERIODE: ' . $BulanIndo[(int)$bulan-1].' '.$tahun); //Kolom bulanKartu tahunKartu
$SI->setCellValue('A4', 'NO'); //Kolom Barang
$SI->setCellValue('B4', 'LOKASI RAK'); //Kolom S.Awal
$SI->setCellValue('C4', 'NAMA BARANG'); //Kolom S.Awal
$SI->setCellValue('D4', 'SURAT JALAN'); //Kolom S.Awal
$SI->setCellValue('E4', 'TANGGAL'); //Kolom S.Awal
$SI->setCellValue('F4', 'QTY'); //Kolom B.Masuk
$SI->setCellValue('G4', 'TOTAL'); //Kolom B.Masuk
$SI->setCellValue('H4', 'KETERANGAN'); //Kolom 



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

  $query = "SELECT rak, brg, suratJln, tgl, jml_msk, total, ket, rowspan
          FROM(
             SELECT rak.rak AS rak, brg, tgl, jam, jml_msk, id_det_msk, MONTH(tgl) AS bulan, YEAR(tgl) AS tahun, ket, suratJln, retur
                FROM detail_masuk
                JOIN masuk AS msk USING(id_msk)
                JOIN detail_brg USING(id)
                JOIN barang USING(id_brg)
                JOIN rak USING(id_rak)
                WHERE retur = '0' AND MONTH(tgl)=$bulan  AND YEAR(tgl)=$tahun
                ORDER BY tgl ASC
          ) AS a
          LEFT JOIN (
                SELECT suratJln, COUNT(suratJln) AS rowspan, SUM(jml_msk) AS total
                FROM detail_masuk
                JOIN masuk AS msk1 USING(id_msk)
                WHERE retur = '0' AND MONTH(tgl)=$bulan  AND YEAR(tgl)=$tahun
                GROUP BY suratJln
                ) AS b USING(suratJln) ORDER BY tgl ASC";

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

      $SI->setCellValue("B".$baris,$val['rak']);
      $SI->setCellValue("C".$baris,$val['brg']);

    if ($index==0)
    {

      $SI->setCellValue("D".$baris,$val['suratJln']);
      $SI->setCellValue("E".$baris,TanggalIndo($val['tgl']));

      // $excelku->getActiveSheet()->mergeCells("C". $baris .":C".$margecell);
      // $excelku->getActiveSheet()->mergeCells("D". $baris .":D".$margecell);
      // $excelku->getActiveSheet()->mergeCells("E". $baris .":E".$margecell);

      $no++;

    }

      $SI->setCellValue("F".$baris,$val['jml_msk']); //mengisi data untuk nomor urut

    if ($index==0)
    {

      if ( $val['rowspan'] > 1 )
      {

        $margecell = ($baris+$val['rowspan'])-1;
        $excelku->getActiveSheet()->mergeCells("A". $baris .":A".$margecell);
        $excelku->getActiveSheet()->mergeCells("D". $baris .":D".$margecell);
        $excelku->getActiveSheet()->mergeCells("E". $baris .":E".$margecell);
        $excelku->getActiveSheet()->mergeCells("G". $baris .":G".$margecell);
        $margecell = "";
      }

      $SI->setCellValue("G".$baris,$val['total']);

    }

    $SI->setCellValue("H".$baris,$val['ket']);

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
$excelku->getActiveSheet()->setTitle('Laporan-TransMskRnci-'.$bulan.'-'.$tahun);

$excelku->setActiveSheetIndex(0);

// untuk excel 2007 atau yang berekstensi .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Laporan-TransMskRnci-'.$bulan.'-'.$tahun.'.xlsx');
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