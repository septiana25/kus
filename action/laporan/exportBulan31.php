<?php

// Set lebar kolom
$excelku->getActiveSheet()->getColumnDimension('A')->setWidth(13);
$excelku->getActiveSheet()->getColumnDimension('B')->setWidth(50);
$excelku->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$excelku->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('F')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('G')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('H')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('I')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('J')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('K')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('L')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('M')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('N')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('O')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('P')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('Q')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('R')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('S')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('T')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('U')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('P')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('W')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('X')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('Y')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('Z')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AA')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AB')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AC')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AD')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AE')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AF')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AG')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AH')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AI')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AJ')->setWidth(8);
$excelku->getActiveSheet()->getColumnDimension('AK')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('AL')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('AM')->setWidth(15);
$excelku->getActiveSheet()->getColumnDimension('AN')->setWidth(15);

// Mergecell, menyatukan beberapa kolom
$excelku->getActiveSheet()->mergeCells('A1:AN1');
$excelku->getActiveSheet()->mergeCells('A2:AN2');
$excelku->getActiveSheet()->mergeCells('F3:AJ3');
$excelku->getActiveSheet()->mergeCells('A3:A4');
$excelku->getActiveSheet()->mergeCells('B3:B4');
$excelku->getActiveSheet()->mergeCells('C3:C4');
$excelku->getActiveSheet()->mergeCells('D3:D4');
$excelku->getActiveSheet()->mergeCells('E3:E4');
$excelku->getActiveSheet()->mergeCells('AK3:AK4');
$excelku->getActiveSheet()->mergeCells('AL3:AL4');
$excelku->getActiveSheet()->mergeCells('AM3:AM4');
$excelku->getActiveSheet()->mergeCells('AN3:AN4');

// Buat Kolom judul tabel
$SI = $excelku->setActiveSheetIndex(0);
$SI->setCellValue('A1', 'LAPORAN TANSAKSI KELUAR GUDANG PT.KHARISMA UTAMA SENTOSA'); //Judul laporan
$SI->setCellValue('A2', 'PERIODE: ' . $BulanIndo[(int)$bulan-1].' '.$tahun); //Judul laporan
$SI->setCellValue('F3', $BulanIndo[(int)$bulan-1].' '.$tahun); //Kolom bulan tahun
$SI->setCellValue('A3', 'NO'); //Kolom rak
$SI->setCellValue('B3', 'NAMA BARANG'); //Kolom Barang
$SI->setCellValue('C3', 'KATEGORI'); //Kolom S.Awal
$SI->setCellValue('D3', 'S.AWAL'); //Kolom S.Awal
$SI->setCellValue('E3', 'B.MASUK'); //Kolom B.Masuk
$SI->setCellValue('F4', '1'); //Kolom 
$SI->setCellValue('G4', '2'); //Kolom 
$SI->setCellValue('H4', '3'); //Kolom 
$SI->setCellValue('I4', '4'); //Kolom 
$SI->setCellValue('J4', '5'); //Kolom 
$SI->setCellValue('K4', '6'); //Kolom 
$SI->setCellValue('L4', '7'); //Kolom 
$SI->setCellValue('M4', '8'); //Kolom 
$SI->setCellValue('N4', '9'); //Kolom 
$SI->setCellValue('O4', '10'); //Kolom 
$SI->setCellValue('P4', '11'); //Kolom 
$SI->setCellValue('Q4', '12'); //Kolom 
$SI->setCellValue('R4', '13'); //Kolom 
$SI->setCellValue('S4', '14'); //Kolom 
$SI->setCellValue('T4', '15'); //Kolom 
$SI->setCellValue('U4', '16'); //Kolom 
$SI->setCellValue('V4', '17'); //Kolom 
$SI->setCellValue('W4', '18'); //Kolom 
$SI->setCellValue('X4', '19'); //Kolom 
$SI->setCellValue('Y4', '20'); //Kolom 
$SI->setCellValue('Z4', '21'); //Kolom 
$SI->setCellValue('AA4', '22'); //Kolom
$SI->setCellValue('AB4', '23'); //Kolom 
$SI->setCellValue('AC4', '24'); //Kolom
$SI->setCellValue('AD4', '25'); //Kolom
$SI->setCellValue('AE4', '26'); //Kolom
$SI->setCellValue('AF4', '27'); //Kolom
$SI->setCellValue('AG4', '28'); //Kolom
$SI->setCellValue('AH4', '29'); //Kolom
$SI->setCellValue('AI4', '30'); //Kolom
$SI->setCellValue('AJ4', '31'); //Kolom
$SI->setCellValue('AK3', 'T.ADJUSMEN'); //Kolom
$SI->setCellValue('AL3', 'T.KELUAR'); //Kolom
$SI->setCellValue('AM3', 'S.AKHIR'); //Kolom
$SI->setCellValue('AN3', 'KODE'); //Kolom
//Mengeset Syle nya
$headerStylenya = new PHPExcel_Style();
$bodyStylenya   = new PHPExcel_Style();

$headerStylenya->applyFromArray(
	array('fill' 	=> array(
		  'type'    => PHPExcel_Style_Fill::FILL_SOLID,
		  'color'   => array('argb' => 'FFEEEEEE')),
      'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
		  'borders' => array('bottom'=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		  )
	));
	
$bodyStylenya->applyFromArray(
	array('fill' 	=> array(
		  'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
		  'color'	=> array('argb' => 'FFFFFFFF')),
		  'borders' => array(
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		  )
    ));


//Menggunakan HeaderStylenya
$excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A3:AN3");
$excelku->getActiveSheet()->setSharedStyle($headerStylenya, "A4:AN4");

// Mengambil data dari tabel
$strsql	= "SELECT s.brg, s_awal, IFNULL(total_masuk, NULL) AS b_masuk,
          tgl_1,  tgl_2,  tgl_3,  tgl_4,  tgl_5,  tgl_6,  tgl_7,  tgl_8,  tgl_9,  tgl_10, tgl_11, tgl_12, tgl_13, tgl_14,
          tgl_15, tgl_16, tgl_17, tgl_18, tgl_19, tgl_20, tgl_21, tgl_22, tgl_23, tgl_24, tgl_25, tgl_26, tgl_27, tgl_28, 
          tgl_29, tgl_30, tgl_31, adjusmen, IFNULL(total_keluar, NULL) AS total_keluar, s_akhir, kat, s.kdbrg
        FROM(
          SELECT id_rak, rak, id_brg, id, kdbrg, brg, SUM(saldo_awal) AS s_awal, SUM(saldo_akhir) AS s_akhir, kat
          FROM detail_brg
          JOIN saldo USING(id)
          JOIN barang USING(id_brg)
          JOIN rak USING(id_rak)
          JOIN kat USING(id_kat)
          WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun
          GROUP BY id_brg
        )s 
        LEFT JOIN(
          SELECT id_rak, id, tgl, id_brg, SUM(jml_klr) AS total_keluar,
            SUM( IF( DAY(tgl)=1 AND status_klr = '0', jml_klr, NULL)) AS tgl_1,
            SUM( IF( DAY(tgl)=2 AND status_klr = '0', jml_klr, NULL)) AS tgl_2,
            SUM( IF( DAY(tgl)=3 AND status_klr = '0', jml_klr, NULL)) AS tgl_3,
            SUM( IF( DAY(tgl)=4 AND status_klr = '0', jml_klr, NULL)) AS tgl_4,
            SUM( IF( DAY(tgl)=5 AND status_klr = '0', jml_klr, NULL)) AS tgl_5,
            SUM( IF( DAY(tgl)=6 AND status_klr = '0', jml_klr, NULL)) AS tgl_6,
            SUM( IF( DAY(tgl)=7 AND status_klr = '0', jml_klr, NULL)) AS tgl_7,
            SUM( IF( DAY(tgl)=8 AND status_klr = '0', jml_klr, NULL)) AS tgl_8,
            SUM( IF( DAY(tgl)=9 AND status_klr = '0', jml_klr, NULL)) AS tgl_9,
            SUM( IF( DAY(tgl)=10 AND status_klr = '0', jml_klr, NULL)) AS tgl_10,
            SUM( IF( DAY(tgl)=11 AND status_klr = '0', jml_klr, NULL)) AS tgl_11,
            SUM( IF( DAY(tgl)=12 AND status_klr = '0', jml_klr, NULL)) AS tgl_12,
            SUM( IF( DAY(tgl)=13 AND status_klr = '0', jml_klr, NULL)) AS tgl_13,
            SUM( IF( DAY(tgl)=14 AND status_klr = '0', jml_klr, NULL)) AS tgl_14,
            SUM( IF( DAY(tgl)=15 AND status_klr = '0', jml_klr, NULL)) AS tgl_15,
            SUM( IF( DAY(tgl)=16 AND status_klr = '0', jml_klr, NULL)) AS tgl_16,
            SUM( IF( DAY(tgl)=17 AND status_klr = '0', jml_klr, NULL)) AS tgl_17,
            SUM( IF( DAY(tgl)=18 AND status_klr = '0', jml_klr, NULL)) AS tgl_18,
            SUM( IF( DAY(tgl)=19 AND status_klr = '0', jml_klr, NULL)) AS tgl_19,
            SUM( IF( DAY(tgl)=20 AND status_klr = '0', jml_klr, NULL)) AS tgl_20,
            SUM( IF( DAY(tgl)=21 AND status_klr = '0', jml_klr, NULL)) AS tgl_21,
            SUM( IF( DAY(tgl)=22 AND status_klr = '0', jml_klr, NULL)) AS tgl_22,
            SUM( IF( DAY(tgl)=23 AND status_klr = '0', jml_klr, NULL)) AS tgl_23,
            SUM( IF( DAY(tgl)=24 AND status_klr = '0', jml_klr, NULL)) AS tgl_24,
            SUM( IF( DAY(tgl)=25 AND status_klr = '0', jml_klr, NULL)) AS tgl_25,
            SUM( IF( DAY(tgl)=26 AND status_klr = '0', jml_klr, NULL)) AS tgl_26,
            SUM( IF( DAY(tgl)=27 AND status_klr = '0', jml_klr, NULL)) AS tgl_27,
            SUM( IF( DAY(tgl)=28 AND status_klr = '0', jml_klr, NULL)) AS tgl_28,
            SUM( IF( DAY(tgl)=29 AND status_klr = '0', jml_klr, NULL)) AS tgl_29,
            SUM( IF( DAY(tgl)=30 AND status_klr = '0', jml_klr, NULL)) AS tgl_30,
            SUM( IF( DAY(tgl)=31 AND status_klr = '0', jml_klr, NULL)) AS tgl_31,
            SUM( IF(status_klr = '1', jml_klr, NULL)) AS adjusmen
          FROM detail_keluar
          LEFT JOIN keluar USING (id_klr)
          LEFT JOIN detail_brg USING(id)
          LEFT JOIN barang USING(id_brg)
          LEFT JOIN rak USING(id_rak)
          WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun
          GROUP BY id_brg
        )k ON k.id_brg=s.id_brg
        LEFT JOIN(
          SELECT id_rak, id, tgl, id_brg, SUM(jml_msk) AS total_masuk
          FROM detail_brg
          LEFT JOIN barang USING(id_brg)
          LEFT JOIN detail_masuk USING(id)
          JOIN masuk ON detail_masuk.id_msk = masuk.id_msk
          LEFT JOIN rak USING(id_rak)
          WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun AND retur !='3'
          GROUP BY id_brg
        )m ON s.id_brg=m.id_brg";
$res    = $koneksi->query($strsql);
$baris  = 5; //Ini untuk dimulai baris datanya, karena di baris 3 itu digunakan untuk header tabel
$no     = 1;
$saldo_awal ="";
$b_masuk    ="";
$tgl_1      ="";
$tgl_2      ="";
$tgl_3      ="";
$tgl_4      ="";
$tgl_5      ="";
$tgl_6      ="";
$tgl_7      ="";
$tgl_8      ="";
$tgl_9      ="";
$tgl_10     ="";
$tgl_11     ="";
$tgl_12     ="";
$tgl_13     ="";
$tgl_14     ="";
$tgl_15     ="";
$tgl_16     ="";
$tgl_17     ="";
$tgl_18     ="";
$tgl_19     ="";
$tgl_20     ="";
$tgl_21     ="";
$tgl_22     ="";
$tgl_23     ="";
$tgl_24     ="";
$tgl_25     ="";
$tgl_26     ="";
$tgl_27     ="";
$tgl_28     ="";
$tgl_29     ="";
$tgl_30     ="";
$tgl_31     ="";
$adj        ="";
$t_akhir    ="";
$s_akhir    ="";

while ($row = $res->fetch_assoc()) {
  $SI->setCellValue("A".$baris,$no); //mengisi data untuk nomor urut
  $SI->setCellValue("B".$baris,utf8_encode($row['brg'])); //mengisi data untuk nama
  $SI->setCellValue("C".$baris,$row['kat']); //mengisi data untuk alamat
  $SI->setCellValue("D".$baris,$row['s_awal']); //mengisi data untuk alamat
  $SI->setCellValue("E".$baris,$row['b_masuk']); //mengisi data untuk TELP
  $SI->setCellValue("F".$baris,$row['tgl_1']); //mengisi data untuk TELP
  $SI->setCellValue("G".$baris,$row['tgl_2']); //mengisi data untuk TELP
  $SI->setCellValue("H".$baris,$row['tgl_3']); //mengisi data untuk TELP
  $SI->setCellValue("I".$baris,$row['tgl_4']); //mengisi data untuk TELP
  $SI->setCellValue("J".$baris,$row['tgl_5']); //mengisi data untuk TELP
  $SI->setCellValue("K".$baris,$row['tgl_6']); //mengisi data untuk TELP
  $SI->setCellValue("L".$baris,$row['tgl_7']); //mengisi data untuk TELP
  $SI->setCellValue("M".$baris,$row['tgl_8']); //mengisi data untuk TELP
  $SI->setCellValue("N".$baris,$row['tgl_9']); //mengisi data untuk TELP
  $SI->setCellValue("O".$baris,$row['tgl_10']); //mengisi data untuk TELP
  $SI->setCellValue("P".$baris,$row['tgl_11']); //mengisi data untuk TELP
  $SI->setCellValue("Q".$baris,$row['tgl_12']); //mengisi data untuk TELP
  $SI->setCellValue("R".$baris,$row['tgl_13']); //mengisi data untuk TELP
  $SI->setCellValue("S".$baris,$row['tgl_14']); //mengisi data untuk TELP
  $SI->setCellValue("T".$baris,$row['tgl_15']); //mengisi data untuk TELP
  $SI->setCellValue("U".$baris,$row['tgl_16']); //mengisi data untuk TELP
  $SI->setCellValue("V".$baris,$row['tgl_17']); //mengisi data untuk TELP
  $SI->setCellValue("W".$baris,$row['tgl_18']); //mengisi data untuk TELP
  $SI->setCellValue("X".$baris,$row['tgl_19']); //mengisi data untuk TELP
  $SI->setCellValue("Y".$baris,$row['tgl_20']); //mengisi data untuk TELP
  $SI->setCellValue("Z".$baris,$row['tgl_21']); //mengisi data untuk TELP
  $SI->setCellValue("AA".$baris,$row['tgl_22']); //mengisi data untuk TELP
  $SI->setCellValue("AB".$baris,$row['tgl_23']); //mengisi data untuk TELP
  $SI->setCellValue("AC".$baris,$row['tgl_24']); //mengisi data untuk TELP
  $SI->setCellValue("AD".$baris,$row['tgl_25']); //mengisi data untuk TELP
  $SI->setCellValue("AE".$baris,$row['tgl_26']); //mengisi data untuk TELP
  $SI->setCellValue("AF".$baris,$row['tgl_27']); //mengisi data untuk TELP
  $SI->setCellValue("AG".$baris,$row['tgl_28']); //mengisi data untuk TELP
  $SI->setCellValue("AH".$baris,$row['tgl_29']); //mengisi data untuk TELP
  $SI->setCellValue("AI".$baris,$row['tgl_30']); //mengisi data untuk TELP
  $SI->setCellValue("AJ".$baris,$row['tgl_31']); //mengisi data untuk TELP
  $SI->setCellValue("AK".$baris,$row['adjusmen']); //mengisi data untuk TELP
  $SI->setCellValue("AL".$baris,$row['total_keluar']); //mengisi data untuk TELP
  $SI->setCellValue("AM".$baris,$row['s_akhir']); //mengisi data untuk TELP
  $SI->setCellValue("AN".$baris,$row['kdbrg']); //mengisi data untuk TELP
  $baris++; //looping untuk barisnya
  $no++;

  $saldo_awal +=$row['s_awal'];
  $b_masuk +=$row['b_masuk'];
  $tgl_1 +=$row['tgl_1'];
  $tgl_2 +=$row['tgl_2'];
  $tgl_3 +=$row['tgl_3'];
  $tgl_4 +=$row['tgl_4'];
  $tgl_5 +=$row['tgl_5'];
  $tgl_6 +=$row['tgl_6'];
  $tgl_7 +=$row['tgl_7'];
  $tgl_8 +=$row['tgl_8'];
  $tgl_9 +=$row['tgl_9'];
  $tgl_10 +=$row['tgl_10'];
  $tgl_11 +=$row['tgl_11'];
  $tgl_12 +=$row['tgl_12'];
  $tgl_13 +=$row['tgl_13'];
  $tgl_14 +=$row['tgl_14'];
  $tgl_15 +=$row['tgl_15'];
  $tgl_16 +=$row['tgl_16'];
  $tgl_17 +=$row['tgl_17'];
  $tgl_18 +=$row['tgl_18'];
  $tgl_19 +=$row['tgl_19'];
  $tgl_20 +=$row['tgl_20'];
  $tgl_21 +=$row['tgl_21'];
  $tgl_22 +=$row['tgl_22'];
  $tgl_23 +=$row['tgl_23'];
  $tgl_24 +=$row['tgl_24'];
  $tgl_25 +=$row['tgl_25'];
  $tgl_26 +=$row['tgl_26'];
  $tgl_27 +=$row['tgl_27'];
  $tgl_28 +=$row['tgl_28'];
  $tgl_29 +=$row['tgl_29'];
  $tgl_30 +=$row['tgl_30'];
  $tgl_31 +=$row['tgl_31'];
  $adj    +=$row['adjusmen'];
  $t_akhir +=$row['total_keluar'];
  $s_akhir +=$row['s_akhir'];

}

$SI->setCellValue("D".$baris,$saldo_awal);
$SI->setCellValue("E".$baris,$b_masuk);
$SI->setCellValue("F".$baris,$tgl_1);
$SI->setCellValue("G".$baris,$tgl_2); //mengisi data untuk TELP
$SI->setCellValue("H".$baris,$tgl_3); //mengisi data untuk TELP
$SI->setCellValue("I".$baris,$tgl_4); //mengisi data untuk TELP
$SI->setCellValue("J".$baris,$tgl_5); //mengisi data untuk TELP
$SI->setCellValue("K".$baris,$tgl_6); //mengisi data untuk TELP
$SI->setCellValue("L".$baris,$tgl_7); //mengisi data untuk TELP
$SI->setCellValue("M".$baris,$tgl_8); //mengisi data untuk TELP
$SI->setCellValue("N".$baris,$tgl_9); //mengisi data untuk TELP
$SI->setCellValue("O".$baris,$tgl_10); //mengisi data untuk TELP
$SI->setCellValue("P".$baris,$tgl_11); //mengisi data untuk TELP
$SI->setCellValue("Q".$baris,$tgl_12); //mengisi data untuk TELP
$SI->setCellValue("R".$baris,$tgl_13); //mengisi data untuk TELP
$SI->setCellValue("S".$baris,$tgl_14); //mengisi data untuk TELP
$SI->setCellValue("T".$baris,$tgl_15); //mengisi data untuk TELP
$SI->setCellValue("U".$baris,$tgl_16); //mengisi data untuk TELP
$SI->setCellValue("V".$baris,$tgl_17); //mengisi data untuk TELP
$SI->setCellValue("W".$baris,$tgl_18); //mengisi data untuk TELP
$SI->setCellValue("X".$baris,$tgl_19); //mengisi data untuk TELP
$SI->setCellValue("Y".$baris,$tgl_20); //mengisi data untuk TELP
$SI->setCellValue("Z".$baris,$tgl_21); //mengisi data untuk TELP
$SI->setCellValue("AA".$baris,$tgl_22); //mengisi data untuk TELP
$SI->setCellValue("AB".$baris,$tgl_23); //mengisi data untuk TELP
$SI->setCellValue("AC".$baris,$tgl_24); //mengisi data untuk TELP
$SI->setCellValue("AD".$baris,$tgl_25); //mengisi data untuk TELP
$SI->setCellValue("AE".$baris,$tgl_26); //mengisi data untuk TELP
$SI->setCellValue("AF".$baris,$tgl_27); //mengisi data untuk TELP
$SI->setCellValue("AG".$baris,$tgl_28); //mengisi data untuk TELP
$SI->setCellValue("AH".$baris,$tgl_29); //mengisi data untuk TELP
$SI->setCellValue("AI".$baris,$tgl_30); //mengisi data untuk TELP
$SI->setCellValue("AJ".$baris,$tgl_31); //mengisi data untuk TELP
$SI->setCellValue("AK".$baris,$adj); //mengisi data untuk TELP
$SI->setCellValue("AL".$baris,$t_akhir); //mengisi data untuk TELP
$SI->setCellValue("AM".$baris,$s_akhir); //mengisi data untuk TELP

//Membuat garis di body tabel (isi data)
$excelku->getActiveSheet()->setSharedStyle($bodyStylenya, "A5:AN$baris");
