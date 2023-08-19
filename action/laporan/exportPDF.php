<?php
require_once '../../function/koneksi.php';
require_once '../../function/api/fpdf/fpdf.php';
// require_once '../../function/api/NotORM.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$kal       = CAL_GREGORIAN;
$bulan     = $_GET['b'];
$tahun     = $_GET['t'];
$hari      = cal_days_in_month($kal, $bulan, $tahun);

$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

if ($hari == 31) {
	require_once 'PDFBulan31.php';
}elseif ($hari == 30) {
	require_once 'PDFBulan30.php';
}elseif ($hari == 28) {
	require_once 'PDFBulan28.php';
}

// Instanciation of inherited class
$pdf = new PDF('L','mm','A3');
$title = 'Laporan Transaksi Keluar Bulan '.$BulanIndo[(int)$bulan-1].' '.$tahun;
$pdf->SetTitle($title);
$pdf->AddPage();
$pdf->SetAuthor('Ian Septiana');
//$pdf->Layout(1,'Pendahuluan', '../../function/api/fpdf/tutorial/20k_c1.txt', 'file','');
//$pdf->Layout(2,'Landasan Teori', '../../function/api/fpdf/tutorial/20k_c2.txt', 'file','');
// Column headings
//$header = array('Country', 'Capital', 'Area (sq km)', 'Pop. (thousands)');
// Data loading
$data = $pdf->LoadData('../../function/api/fpdf/tutorial/countries.txt');
//$pdf->Layout(3,'Perancangan', $data, 'csv', $header);
$pdf->Layout(1,'Rak', $data, 'database', $datas);
$pdf->Output();
?>