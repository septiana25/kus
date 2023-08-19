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

$id_brgKartu = $koneksi->real_escape_string($_GET['id']);
$bulanKartu  = $koneksi->real_escape_string($_GET['b']);
$tahunKartu  = $koneksi->real_escape_string($_GET['t']);

  //echo "Pada Bulan ini Terdapat".$hari1."hari";
  //aray bulan
  $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

  $excelku = new PHPExcel();

  // Set properties
  $excelku->getProperties()->setCreator("Ian Septiana")
                           ->setLastModifiedBy("Ian Septiana");


  // isi


  // Set lebar kolom
  $excelku->getActiveSheet()->getColumnDimension('A')->setWidth(10);
  $excelku->getActiveSheet()->getColumnDimension('B')->setWidth(22);
  $excelku->getActiveSheet()->getColumnDimension('C')->setWidth(15);
  $excelku->getActiveSheet()->getColumnDimension('D')->setWidth(30);
  $excelku->getActiveSheet()->getColumnDimension('E')->setWidth(15);
  $excelku->getActiveSheet()->getColumnDimension('F')->setWidth(15);
  $excelku->getActiveSheet()->getColumnDimension('G')->setWidth(15);


  // Mergecell, menyatukan beberapa kolom
  $excelku->getActiveSheet()->mergeCells('A1:G1');
  $excelku->getActiveSheet()->mergeCells('A2:G2');
  $excelku->getActiveSheet()->mergeCells('A3:G3');
  $excelku->getActiveSheet()->mergeCells('A4:G4');
  $excelku->getActiveSheet()->mergeCells('B6:C6');
  $excelku->getActiveSheet()->mergeCells('D6:G6');


  // Buat Kolom judul tabel


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
  $excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:G6");
  // $excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:AI4");

  // $SI->setCellValue("A".$baris,$no); //mengisi data untuk nomor urut


  //Membuat garis di body tabel (isi data)
  // $excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A5:AI$baris");


  $cekSaldo = $koneksi->query("SELECT brg, SUM(saldo_awal) AS saldo_awal FROM saldo
                    JOIN detail_brg USING(id)
                    JOIN barang USING(id_brg)
                  WHERE id_brg = $id_brgKartu AND MONTH(tgl) = $bulanKartu AND YEAR(tgl)=$tahunKartu ");
    $rowSaldo  =  $cekSaldo->fetch_assoc();
    $SaldoAwal =  $rowSaldo['saldo_awal'];
    $brg       =  $rowSaldo['brg'];


    $query = "SELECT suratJln, msk.tgl AS tgl_msk, NULL AS toko, SUM(jml_msk) AS msk, NULL AS klr FROM detail_masuk
          JOIN masuk AS msk USING(id_msk)
          JOIN detail_brg USING(id)
          JOIN barang USING(id_brg)
        WHERE MONTH(msk.tgl) = $bulanKartu AND YEAR(msk.tgl)=$tahunKartu AND id_brg =$id_brgKartu AND retur !='3'
        GROUP BY suratJln

        UNION ALL

        SELECT no_faktur, klr.tgl AS tgl_klr, toko, NULL, SUM(jml_klr) AS klr FROM detail_keluar
          JOIN keluar AS klr USING(id_klr)
          JOIN detail_brg USING(id)
          JOIN barang USING(id_brg)
          JOIN toko USING(id_toko)
        WHERE MONTH(klr.tgl) = $bulanKartu AND YEAR(klr.tgl)=$tahunKartu AND id_brg =$id_brgKartu
        GROUP BY no_faktur ";

    $rest = $koneksi->query($query);
    $fetch = $rest->fetch_all(MYSQL_ASSOC);
  //echo "<pre>". print_r($fetch); die;

      foreach($fetch as $c => $key) {
          //$sort_faktur[] = $key['suratJln'];
          $sort_tgl[] = $key['tgl_msk'];
          $sort_msk[] = $key['msk'];

      }

      if ($rest->num_rows > 0) {

        // Buat Kolom judul tabel
        $SI = $excelku->setActiveSheetIndex(0);
        $SI->setCellValue('A1', 'LAPORAN AKTIVITAS UNIT KELUAR - MASUK BARANG (RINCI)'); //Judul laporan
        $SI->setCellValue('A2', 'PT. KHARISMA UTAMA SENTOSA'); //Judul laporan
        $SI->setCellValue('A3', 'PERIODE: ' . $BulanIndo[(int)$bulanKartu-1].' '.$tahunKartu); //Kolom bulanKartu tahunKartu
        $SI->setCellValue('A4', $brg); //Kolom Barang
        $SI->setCellValue('A5', 'NO'); //Kolom S.Awal
        $SI->setCellValue('B6', 'SALDO AWAL'); //Kolom S.Awal
        $SI->setCellValue('D6', $SaldoAwal); //Kolom S.Awal
        $SI->setCellValue('B5', 'NO FAKTUR'); //Kolom S.Awal
        $SI->setCellValue('C5', 'TGL FAKTUR'); //Kolom B.Masuk
        $SI->setCellValue('D5', 'PELANGGAN'); //Kolom 
        $SI->setCellValue('E5', 'MASUK'); //Kolom 
        $SI->setCellValue('F5', 'KELUAR'); //Kolom 
        $SI->setCellValue('G5', 'SALDO AKHIR'); //Kolom 
        
        $no         = 1;
        $baris      = 7;
        $awal       = $SaldoAwal;
        $saldo      = "";
        $totalMSK   = "";
        $totalKLR   = "";

        array_multisort($sort_tgl, SORT_ASC, $sort_msk, SORT_DESC, $fetch);

        foreach ($fetch as $key => $val) {

          if (empty($val['msk'])) {
            $saldo = $awal-$val['klr'];
            $msk = $val['msk'];
          }else{
            $saldo = $awal+$val['msk'];
            $msk = $val['msk'];
          }

          $SI->setCellValue("A".$baris,$no); //mengisi data untuk nomor urut
          $SI->setCellValue("B".$baris,$val['suratJln']); //mengisi data untuk nomor urut
          $SI->setCellValue("C".$baris,TanggalIndo($val['tgl_msk'])); //mengisi data untuk nomor urut
          $SI->setCellValue("D".$baris,$val['toko']); //mengisi data untuk nomor urut
          $SI->setCellValue("E".$baris,$msk); //mengisi data untuk nomor urut
          $SI->setCellValue("F".$baris,$val['klr']); //mengisi data untuk nomor urut
          $SI->setCellValue("G".$baris,$saldo); //mengisi data untuk nomor urut
            
          $awal = $saldo;

          $no++;
          $baris++;
          $totalMSK   +=$val['msk'];
          $totalKLR   +=$val['klr'];
          // $totalSaldo +=$saldo;
            
          }
          //$sAwal =  $val['s_awal'] + $val['tmbh_saldo'];

          $SI->setCellValue("E".$baris,$totalMSK); //mengisi data untuk nomor urut
          $SI->setCellValue("F".$baris,$totalKLR); //mengisi data untuk nomor urut
          $SI->setCellValue("G".$baris,$saldo); //mengisi data untuk nomor urut

          //Menggunakan HeaderStylenya
          $excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A" . $baris . ":G" . $baris);
          
          // MERGE CELL
          $excelku->getActiveSheet()->mergeCells("A" . $baris . ":D" . $baris);


      }
      else
      {
        $SI = $excelku->setActiveSheetIndex(0);
        $SI->setCellValue('A1', 'LAPORAN YANG ANDA MINTA TIDAK ADA'); //Judul laporan
        $baris = 7;
      }


  // isi
  //Membuat garis di body tabel (isi data)
  $excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A7:G$baris");

  //Memberi nama sheet
  $excelku->getActiveSheet()->setTitle('Laporan-STOK-'.$bulanKartu.'-'.$tahunKartu);

  $excelku->setActiveSheetIndex(0);

  // untuk excel 2007 atau yang berekstensi .xlsx
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename=Laporan-STOK-'.$bulanKartu.'-'.$tahunKartu.'.xlsx');
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