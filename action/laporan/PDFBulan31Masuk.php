<?php
$query = "SELECT s.brg, s_awal, IFNULL(total_keluar, 0) AS total_keluar,
  tgl_1,  tgl_2,  tgl_3,  tgl_4,  tgl_5,  tgl_6,  tgl_7,  tgl_8,  tgl_9,  tgl_10, tgl_11, tgl_12, tgl_13, tgl_14,
  tgl_15, tgl_16, tgl_17, tgl_18, tgl_19, tgl_20, tgl_21, tgl_22, tgl_23, tgl_24, tgl_25, tgl_26, tgl_27, tgl_28, 
  tgl_29, tgl_30, tgl_31, IFNULL(total_masuk, 0) AS b_masuk, s_akhir
FROM(
  SELECT id_rak, rak, id_brg, id, brg, SUM(saldo_awal) AS s_awal, SUM(saldo_akhir) AS s_akhir
  FROM detail_brg
  JOIN saldo USING(id)
  JOIN barang USING(id_brg)
  JOIN rak USING(id_rak)
  WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun
  GROUP BY id_brg
)s 
LEFT JOIN(
  SELECT id_rak, id, tgl, id_brg, SUM(jml_klr) AS total_keluar
  FROM detail_keluar
  LEFT JOIN keluar USING (id_klr)
  LEFT JOIN detail_brg USING(id)
  LEFT JOIN barang USING(id_brg)
  LEFT JOIN rak USING(id_rak)
  WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun
  GROUP BY id_brg
)k ON k.id_brg=s.id_brg
LEFT JOIN(
  SELECT id_rak, id, id_det_msk, tgl, id_brg, SUM(jml_msk) AS total_masuk,

    SUM( IF( DAY(tgl)=1, jml_msk, NULL)) AS tgl_1,
    SUM( IF( DAY(tgl)=2, jml_msk, NULL)) AS tgl_2,
    SUM( IF( DAY(tgl)=3, jml_msk, NULL)) AS tgl_3,
    SUM( IF( DAY(tgl)=4, jml_msk, NULL)) AS tgl_4,
    SUM( IF( DAY(tgl)=5, jml_msk, NULL)) AS tgl_5,
    SUM( IF( DAY(tgl)=6, jml_msk, NULL)) AS tgl_6,
    SUM( IF( DAY(tgl)=7, jml_msk, NULL)) AS tgl_7,
    SUM( IF( DAY(tgl)=8, jml_msk, NULL)) AS tgl_8,
    SUM( IF( DAY(tgl)=9, jml_msk, NULL)) AS tgl_9,
    SUM( IF( DAY(tgl)=10, jml_msk, NULL)) AS tgl_10,
    SUM( IF( DAY(tgl)=11, jml_msk, NULL)) AS tgl_11,
    SUM( IF( DAY(tgl)=12, jml_msk, NULL)) AS tgl_12,
    SUM( IF( DAY(tgl)=13, jml_msk, NULL)) AS tgl_13,
    SUM( IF( DAY(tgl)=14, jml_msk, NULL)) AS tgl_14,
    SUM( IF( DAY(tgl)=15, jml_msk, NULL)) AS tgl_15,
    SUM( IF( DAY(tgl)=16, jml_msk, NULL)) AS tgl_16,
    SUM( IF( DAY(tgl)=17, jml_msk, NULL)) AS tgl_17,
    SUM( IF( DAY(tgl)=18, jml_msk, NULL)) AS tgl_18,
    SUM( IF( DAY(tgl)=19, jml_msk, NULL)) AS tgl_19,
    SUM( IF( DAY(tgl)=20, jml_msk, NULL)) AS tgl_20,
    SUM( IF( DAY(tgl)=21, jml_msk, NULL)) AS tgl_21,
    SUM( IF( DAY(tgl)=22, jml_msk, NULL)) AS tgl_22,
    SUM( IF( DAY(tgl)=23, jml_msk, NULL)) AS tgl_23,
    SUM( IF( DAY(tgl)=24, jml_msk, NULL)) AS tgl_24,
    SUM( IF( DAY(tgl)=25, jml_msk, NULL)) AS tgl_25,
    SUM( IF( DAY(tgl)=26, jml_msk, NULL)) AS tgl_26,
    SUM( IF( DAY(tgl)=27, jml_msk, NULL)) AS tgl_27,
    SUM( IF( DAY(tgl)=28, jml_msk, NULL)) AS tgl_28,
    SUM( IF( DAY(tgl)=29, jml_msk, NULL)) AS tgl_29,
    SUM( IF( DAY(tgl)=30, jml_msk, NULL)) AS tgl_30,
    SUM( IF( DAY(tgl)=31, jml_msk, NULL)) AS tgl_31

  FROM detail_brg
  LEFT JOIN barang USING(id_brg)
  LEFT JOIN masuk USING(id)
  LEFT JOIN detail_masuk USING(id_msk)
  LEFT JOIN rak USING(id_rak)
  WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun
  GROUP BY id_brg
)m ON s.id_brg=m.id_brg";

  //WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun
$datas = $koneksi->query($query);


class PDF extends FPDF{

	function Header(){
		global $title;
		$w = $this->GetStringWidth($title)+6;
    	$this->SetX((420-$w)/2);
		// Logo
	    //$this->Image('../../function/api/fpdf/tutorial/logo.png',10,6,30);
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Thickness of frame (1 mm)
    	$this->SetLineWidth(1);
	    // Title
	    $this->Cell($w,10,$title,0,0,'C');
	    // Line break
	    $this->Ln(20);
	}

	// Load data
	function LoadData($file)
	{
	    // Read file lines
	    $lines = file($file);
	    $data = array();
	    foreach($lines as $line)
	        $data[] = explode(';',trim($line));
	    return $data;
	}


	// function Chapter($num, $label){
	//     // Arial 12
	//     $this->SetFont('Times','B',12);
	//     // Background color
	//     $this->SetFillColor(200,220,255);
	//     // Title
	//     $this->Cell(0,6,"Chapter $num $label",0,1,'L',true);
	//     // Line break
	//     $this->Ln(4);
	// }

	function Mybody($file, $type, $datas){
		if ($type=='file') {
		    // Read text file
		    $txt = file_get_contents($file);
		    // Times 12
		    $this->SetFont('Times','',12);
		    // Output justified text
		    $this->MultiCell(0,5,$txt);
		    // Line break
		    $this->Ln();
		}else if ($type=='csv') {
		    // Column widths
		    $w = array(40, 35, 40, 45);
		    // Header
		    for($i=0;$i<count($datas);$i++)
		        $this->Cell($w[$i],7,$datas[$i],1,0,'C');
		    $this->Ln();
		    // Data
		    foreach($file as $row)
		    {
		        $this->Cell($w[0],6,$row[0],'LR');
		        $this->Cell($w[1],6,$row[1],'LR');
		        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
		        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
		        $this->Ln();
		    }
		    // Closing line
		    $this->Cell(array_sum($w),0,'','T');
		}else if($type == 'database'){
			$this->Ln();
			$this->SetFont('Times','B',10);
			$this->Cell(14,5,'No',1,0,'C');
			$this->Cell(75,5,'Nama Barang',1,0,'C');
			$this->Cell(14,5,'S.Awal',1,0,'C');
			$this->Cell(14,5,'B.Keluar',1,0,'C');
			$this->Cell(8,5,'1',1,0,'C');
			$this->Cell(8,5,'2',1,0,'C');
			$this->Cell(8,5,'3',1,0,'C');
			$this->Cell(8,5,'4',1,0,'C');
			$this->Cell(8,5,'5',1,0,'C');
			$this->Cell(8,5,'6',1,0,'C');
			$this->Cell(8,5,'7',1,0,'C');
			$this->Cell(8,5,'8',1,0,'C');
			$this->Cell(8,5,'9',1,0,'C');
			$this->Cell(8,5,'10',1,0,'C');
			$this->Cell(8,5,'11',1,0,'C');
			$this->Cell(8,5,'12',1,0,'C');
			$this->Cell(8,5,'13',1,0,'C');
			$this->Cell(8,5,'14',1,0,'C');
			$this->Cell(8,5,'15',1,0,'C');
			$this->Cell(8,5,'16',1,0,'C');
			$this->Cell(8,5,'17',1,0,'C');
			$this->Cell(8,5,'18',1,0,'C');
			$this->Cell(8,5,'19',1,0,'C');
			$this->Cell(8,5,'20',1,0,'C');
			$this->Cell(8,5,'21',1,0,'C');
			$this->Cell(8,5,'22',1,0,'C');
			$this->Cell(8,5,'23',1,0,'C');
			$this->Cell(8,5,'24',1,0,'C');
			$this->Cell(8,5,'25',1,0,'C');
			$this->Cell(8,5,'26',1,0,'C');
			$this->Cell(8,5,'27',1,0,'C');
			$this->Cell(8,5,'28',1,0,'C');
			$this->Cell(8,5,'29',1,0,'C');
			$this->Cell(8,5,'30',1,0,'C');
			$this->Cell(8,5,'31',1,0,'C');
			$this->Cell(14,5,'T.Masuk',1,0,'C');
			$this->Cell(14,5,'S.Akhir',1,0,'C');
			$this->Ln();

			$no=1;
			while ($row = $datas->fetch_array()) {
				$this->Cell(14,5,$no,1,0,'C');
				$this->Cell(75,5,utf8_encode($row[0]),1,0,'L');
				$this->Cell(14,5,$row[1],1,0,'C');
				$this->Cell(14,5,$row[2],1,0,'C');
				$this->Cell(8,5,$row[3],1,0,'C');
				$this->Cell(8,5,$row[4],1,0,'C');
				$this->Cell(8,5,$row[5],1,0,'C');
				$this->Cell(8,5,$row[6],1,0,'C');
				$this->Cell(8,5,$row[7],1,0,'C');
				$this->Cell(8,5,$row[8],1,0,'C');
				$this->Cell(8,5,$row[9],1,0,'C');
				$this->Cell(8,5,$row[10],1,0,'C');
				$this->Cell(8,5,$row[11],1,0,'C');
				$this->Cell(8,5,$row[12],1,0,'C');
				$this->Cell(8,5,$row[13],1,0,'C');
				$this->Cell(8,5,$row[14],1,0,'C');
				$this->Cell(8,5,$row[15],1,0,'C');
				$this->Cell(8,5,$row[16],1,0,'C');
				$this->Cell(8,5,$row[17],1,0,'C');
				$this->Cell(8,5,$row[18],1,0,'C');
				$this->Cell(8,5,$row[19],1,0,'C');
				$this->Cell(8,5,$row[20],1,0,'C');
				$this->Cell(8,5,$row[21],1,0,'C');
				$this->Cell(8,5,$row[22],1,0,'C');
				$this->Cell(8,5,$row[23],1,0,'C');
				$this->Cell(8,5,$row[24],1,0,'C');
				$this->Cell(8,5,$row[25],1,0,'C');
				$this->Cell(8,5,$row[26],1,0,'C');
				$this->Cell(8,5,$row[27],1,0,'C');
				$this->Cell(8,5,$row[28],1,0,'C');
				$this->Cell(8,5,$row[29],1,0,'C');
				$this->Cell(8,5,$row[30],1,0,'C');
				$this->Cell(8,5,$row[31],1,0,'C');
				$this->Cell(8,5,$row[32],1,0,'C');
				$this->Cell(8,5,$row[33],1,0,'C');
				$this->Cell(14,5,$row[34],1,0,'C');
				$this->Cell(14,5,$row[35],1,0,'C');
				$this->Ln();
				$no++;
			}
			/*foreach ($datas as $row) {
				$this->Cell(20,7,$row['id_det_klr'],1,0,'C');
				$this->Cell(25,7,$row['id_klr'],1,0,'C');
				$this->Cell(25,7,$row['id'],1,0,'C');
				$this->Cell(25,7,$row['jml_klr'],1,0,'C');
				$this->Cell(25,7,$row['jam'],1,0,'C');
				$this->Ln();
			}
*/		}

	}

	function Layout($num, $label, $file, $type, $datas){
		
		//$this->Chapter($num,$label);
		$this->Mybody($file,$type,$datas);
	}

	function Footer(){
	    // Position at 1.5 cm from bottom
	    $this->SetY(-15);
	    // Arial italic 8
	    $this->SetFont('Times','',12);
	    // Page number
	    $this->Cell(0,10,$this->PageNo(),0,0,'R');
	}
}
?>