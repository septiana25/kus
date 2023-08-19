<?php

require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
require_once 'function/tgl_indo.php';
require_once 'function/fungsi_rupiah.php';

$bulan     = $_POST['bulan'];
$tahun     = $_POST['tahun'];

//array bulan
$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");


$lapClaim = "SELECT pengaduan, dealer, daerah, tglNota, toko, b.brg, k.kat, pattern, dot, kerusakan, tread, keputusan, nominal, tahun, noCM, tglCM
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
ORDER BY pengaduan ASC, kat
";

$result1 = $koneksi->query($lapClaim);
$fetch = $result1->fetch_all(MYSQL_ASSOC);

if ($result1->num_rows >= 1) {
//jika data ada
// echo "<pre>". print_r($fetch); die;
foreach ($fetch as $key => $val) 
{
  $result[$val['kat']][] = $val;

}
// echo '<pre>'.print_r($result, true).'</pre>';
echo '

	<html>
		<head>
		<title>Lapran Claim </title>
			<style>
				body {font-family:"segoe ui", "open sans", tahoma, arial}
				table {border-collapse: collapse}
	
				
				.total td {background-color: #f5f5f5 !important;}
				.right{text-align: right}
				table tr:nth-child(odd) td {
					background-color: #fbfbfb;
					border-bottom: 1px solid #efefef;
					border-top: 1px solid #ececec;
				}
				table th {
					color: #616161;
					margin: 0;
					padding: 10px 10px;
					border: 1px solid #e4e4e4;
					text-align: center;
					font-size: 13.5px;
					
					background: #efefef;

				}
				table td {
					border-right: 1px solid #ececec;
					border-left: 1px solid #ececec;
					padding: 7px 15px;
					color: #676767;
					font-size: 13px;
				}
				/*table td:nth-child(n+3) {
					text-align: right;
				}*/
				td#kategori 
				{
				    background: #676767;
				    color: white;
				    text-align: center;
				    font-weight: bold;
				}

				.headLap{
					text-align: center;
					text-transform: uppercase;
					color: #676767;
				}
				.headLap #marginLap 
				{
					margin-bottom: -15px;
				}
				
				.atasTable{
					font-size: 13px;
				    color: #676767;
				    font-weight: bold;
				}

				.atasTable p 
				{
				    float: left;
				    margin-left: 7px;
				    margin-bottom: 5px;
				}

				p#daerah 
				{
				    margin-left: 152px;
				}

				p#tgl 
				{
				    margin-left: 450px;
				}

				.kanan 
				{
					text-align: right;
				}

			</style>
		</head>
		<body>


';

echo '
		<div class="headLap">
			<h3 id="marginLap">CV. Kharisma Tiara Abadi</h3>
			<h3>Hasil Pemeriksaan Ban-ban Pengaduan</h3>
		</div>
		<div class="atasTable">
			<p>Tempat Pemeriksaan : '.$val['dealer'].'</p>
			<p id="daerah">Daerah : '.$val['daerah'].'</p>
			<p id="tgl">Tanggal : '.TanggalIndo($val['tglNota']).'</p>
		</div>
';

echo '
			<table width="100%">
				<thead>
					
					<tr>
						<th >No</th>
						<th>Toko</th>
						<th width="10px">No Urut</th>
						<th>Ukuran</th>
						<th>Pattern</th>
						<th>DOT</th>
						<th>Kerusakan</th>
            <th>No. CM</th>
						<th>Tgl CM</th>
						<th>Keputusan</th>
						<th>Nominal</th>
					</tr>
				</thead>
				<tbody>

';
$no = "";
foreach ($result as $kat => $array)
{
	$no=1;
	foreach ($array as $index => $val) {
        if ($index==0) 
        {
         	echo '
	         		<tr>
	         			<tr>
	         				<td colspan="12" id="kategori">'.$val['kat'].'</td>
	         			</tr>';
	    } 
			echo '
					
						<td>'.$val['pengaduan'].'</td>
						<td>'.$val['toko'].'</td>
						<td>'.$no.'</td>
	        			<td>'.$val['brg'].'</td>
	        			<td>'.$val['pattern'].'</td>
	        			<td>'.$val['dot'].'-'.$val['tahun'].'</td>
	        			<td>'.$val['kerusakan'].'</td>
                		<td>'.$val['noCM'].'</td>
	        			<td>'.$val['tglCM'].'</td>
	        			<td>'.$val['keputusan'].'</td>
	        			<td class="kanan">'.format_rupiah($val['nominal']).'</td>
					</tr>
	        ';


			$no++;

	}
	echo '<tr></tr>';	
}
echo '
				</tbody>
			</table>
		</body>
	</html>
';


}
else
{
	echo '
		<p>Laporan Claim Bulan '.$BulanIndo[(int)$bulan - 1].' '.$tahun.' Tidak Ada</p>
	';
}
// $tgl = "2017-07-26";
// $tahun = substr($tgl, 2,2);
// $bulan = substr($tgl,5,2);
// $reg='001';
// echo 'KTA'.$bulan.$tahun.$reg;
?>