<?php
require_once '../../function/koneksi.php';
require_once '../../function/api/fpdf/fpdf.php';
// require_once '../../function/api/NotORM.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../../function/fungsi_rupiah.php';

$kal       = CAL_GREGORIAN;
$bulan     = 7;
$tahun     = 2017;
$hari      = cal_days_in_month($kal, $bulan, $tahun);

$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

$query = "SELECT pengaduan, dealer, daerah, tglNota, toko, b.brg, k.kat, pattern, dot, kerusakan, tread, keputusan, nominal, tahun, noCM, tglCM
		FROM(
		SELECT pengaduan, dealer, daerah, tglNota, toko, brg, pattern, dot, kerusakan, tread, keputusan, nominal, tahun, noCM, tglCM
		FROM tblNota
		JOIN tblDetNota USING(idNota)
		JOIN claim USING(id_claim)
		JOIN barang USING(id_brg)
		)b
		LEFT JOIN(
		  SELECT kat, brg
		  FROM barang
		  JOIN claim USING(id_brg)
		  JOIN kat USING(id_kat)
		)k ON b.brg=k.brg
		WHERE MONTH(tglNota) = '$bulan' AND YEAR(tglNota) = '$tahun'
		GROUP BY kat, pengaduan
		ORDER BY pengaduan ASC, kat";
// $datas = $koneksi->query($query);

$result1 = $koneksi->query($query);
$fetch = $result1->fetch_all(MYSQL_ASSOC);

// echo "<pre>". print_r($fetch); die;
foreach ($fetch as $key => $val) 
{
  $result[$val['kat']][] = $val;

}

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
			$this->SetFont('Times','B',12);
			$this->Cell(11,5,'No',1,0,'C');
			$this->Cell(50,5,'Toko',1,0,'C');
			$this->Cell(16,5,'No Urut',1,0,'C');
			$this->Cell(85,5,'Ukuran',1,0,'C');
			$this->Cell(20,5,'Pattern',1,0,'C');
			$this->Cell(23,5,'DOT',1,0,'C');
			$this->Cell(100,5,'Kerusakan',1,0,'C');
			$this->Cell(20,5,'No CM',1,0,'C');
			$this->Cell(20,5,'Tgl CM',1,0,'C');
			$this->Cell(21,5,'Keputusan',1,0,'C');
			$this->Cell(20,5,'Nominal',1,0,'C');
			$this->Ln();
			$no="";

			foreach ($result as $kat => $array) 
			{
				$no=1;
				foreach ($array as $index => $val) {
					$this->SetFont('Times','',10);
					$this->Cell(11,5,$val['pengaduan'],1,0,'C');
					$this->Cell(50,5,$val['toko'],1,0,'L');
					$this->Cell(16,5,$no,1,0,'C');
					$this->Cell(85,5,$val['brg'],1,0,'L');
					$this->Cell(20,5,$val['pattern'],1,0,'L');
					$this->Cell(23,5,$val['dot'],1,0,'L');
					$this->Cell(100,5,$val['kerusakan'],1,0,'L');
					$this->Cell(20,5,$val['noCM'],1,0,'L');
					$this->Cell(20,5,$val['tglCM'],1,0,'C');
					$this->Cell(21,5,$val['keputusan'],1,0,'C');
					$this->Cell(20,5,format_rupiah($val['nominal']),1,0,'R');
					$this->Ln();
				$no++;
					}	
			}
			// while ($row = $datas->fetch_array()) {

			// }
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