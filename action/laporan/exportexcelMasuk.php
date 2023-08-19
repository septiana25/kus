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

$kal       = CAL_GREGORIAN;
$bulan     = $_GET['b'];
$tahun     = $_GET['t'];
$hari      = cal_days_in_month($kal, $bulan, $tahun);

//echo "Pada Bulan ini Terdapat".$hari1."hari";
//aray bulan
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

$excelku = new PHPExcel();

// Set properties
$excelku->getProperties()->setCreator("Ian Septiana")
                         ->setLastModifiedBy("Ian Septiana");
  if ($hari == 31) {
    require_once 'exportBulan31Masuk.php';
  }
  else if ($hari == 30) {
    require_once 'exportBulan30Masuk.php';
  }
  else if ($hari == 29) {
    require_once 'exportBulan29Masuk.php';
  }
  else if ($hari == 28) {
    require_once 'exportBulan28Masuk.php';
  }

//Memberi nama sheet
$excelku->getActiveSheet()->setTitle('Laporan-Masuk-'.$bulan.'-'.$tahun);

$excelku->setActiveSheetIndex(0);

// untuk excel 2007 atau yang berekstensi .xlsx
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Laporan-Masuk-'.$bulan.'-'.$tahun.'.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($excelku, 'Excel2007');
$objWriter->save('php://output');
exit;

?>