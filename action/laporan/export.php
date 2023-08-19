<?php 
//memanggil fungsi
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
include 'fungsi.php';
$hari1     = $_GET['h'];
$bulan     = $_GET['b'];
$tahun     = $_GET['t'];
$hari      = $hari1 - 3;

  if ($hari1 == 34) {
    require_once 'bulan_31.php';
  }
  if ($hari1 == 33) {
    require_once 'bulan_30.php';
  }
  if ($hari1 == 31) {
    require_once 'bulan_28.php';
  }

$result = $koneksi->query($sql);


//pengaturan nama file
$namaFile = "Laporan-transaksi-".$bulan."-".$tahun.".xls";
//pengaturan judul data
$judul = "LAPORAN TRANSAKSI INVENTORI GUDANG CV.KHARISMA TIARA ABADI";
//baris berapa header tabel di tulis
$tablehead = 2;
//baris berapa data mulai di tulis
$tablebody = 3;
//no urut data
$nourut = 1;

//penulisan header
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=".$namaFile."");
header("Content-Transfer-Encoding: binary ");


xlsBOF();
 
xlsWriteLabel(0,0,$judul);  

$kolomhead = 0;

xlsWriteLabel($tablehead,$kolomhead++,"LOKASI RAK");              
xlsWriteLabel($tablehead,$kolomhead++,"NAMA BARANG");             
xlsWriteLabel($tablehead,$kolomhead++,"SALDO AWAL");
xlsWriteLabel($tablehead,$kolomhead++,"BARANG MASUK");
for ($i=1; $i <= $hari ; $i++)
	{ 
       xlsWriteNumber($tablehead,$kolomhead++,"$i");
    }
xlsWriteLabel($tablehead,$kolomhead++,"TOTAL KELUAR");
xlsWriteLabel($tablehead,$kolomhead++,"SALDO AKHIR");

while ($data = $result->fetch_array())
{
$kolombody = 0;

//gunakan xlsWriteNumber untuk penulisan nomor dan xlsWriteLabel untuk penulisan string
xlsWriteLabel($tablebody,$kolombody++,$data['rak']);
xlsWriteLabel($tablebody,$kolombody++,utf8_encode($data['brg']));
xlsWriteNumber($tablebody,$kolombody++,$data['s_awal']);
xlsWriteNumber($tablebody,$kolombody++,$data['b_masuk']);
for ($i=4; $i <= $hari1 ; $i++)
	{ 
       xlsWriteNumber($tablebody,$kolombody++,$data[$i]);
    }
xlsWriteNumber($tablebody,$kolombody++,$data['total_keluar']);
xlsWriteNumber($tablebody,$kolombody++,$data['s_akhir']);
$tablebody++;
//$nourut++;
}

xlsEOF();
exit();
?>