<?php

require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
require_once 'function/tgl_indo.php';
require_once 'function/fungsi_rupiah.php';

// $batsLimit    = $koneksi->real_escape_string($_POST['batsLimit']);

$bulan        = date("m");
$tahun        = date("Y");
// $batsLimit = 5;
$today        = date("Y-m-d"); 

$lapClaim = "SELECT b.id_brg, b.rak, b.brg, b.kat, IFNULL(saldo_akhir, 0) AS akhir
			FROM(
			SELECT id_brg, rak, brg, saldo_awal, saldo_akhir, tgl
			FROM detail_brg
			RIGHT JOIN barang USING(id_brg) 
			LEFT JOIN rak USING(id_rak)
			LEFT JOIN saldo USING(id)
			)d
			RIGHT JOIN (
			SELECT id_brg, rak, brg, kat
			FROM barang
			LEFT JOIN detail_brg USING(id_brg)
			LEFT JOIN rak USING(id_rak)
			LEFT JOIN kat USING(id_kat)
			)b ON b.brg=d.brg AND b.rak=d.rak
			WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun AND saldo_akhir <= 5
			ORDER BY kat, rak ASC, akhir
";

$result1 = $koneksi->query($lapClaim);
$fetch = $result1->fetch_all(MYSQL_ASSOC);

// echo "<pre>". print_r($fetch); die;
foreach ($fetch as $key => $val) 
{
  $result[$val['kat']][] = $val;

}
// echo '<pre>'.print_r($result, true).'</pre>';

echo '
			<style>
				body {font-family:"segoe ui", "open sans", tahoma, arial}
				table {border-collapse: collapse}
	
				
				.total td {background-color: #f5f5f5 !important;}
				.right{text-align: right}
				table tr:nth-child(odd) td {
					background-color: #fbfbfb;
					border-bottom: 2px solid #585656;
					border-top: 2px solid #5a5353;
				}
				table th {
					color: #616161;
					margin: 0;
					padding: 10px 10px;
					border: 2px solid #5a5353;
					text-align: center;
					font-size: 13.5px;
					
					background: #585656;

				}
				table td {
					border-right: 2px solid #5a5353;
					border-left: 2px solid #5a5353;
					padding: 7px 15px;
					color: #5a5353;
					font-size: 13px;
				}
				/*table td:nth-child(n+3) {
					text-align: right;
				}*/
				td#kategori {
				    background: #5a5353;
				}

				.headLap{
					text-align: center;
					text-transform: uppercase;
					color: #5a5353;
				}
				.headLap #marginLap {
					margin-bottom: -15px;
				}
				
				.atasTable{
					font-size: 13px;
				    color: #676767;
				    font-weight: bold;
				}

				.atasTable p {
				    float: left;
				    margin-left: 7px;
				    margin-bottom: 5px;
				}

				p#daerah {
				    margin-left: 152px;
				}

				p#tgl {
				    margin-left: 900px;
				}

				#kat{
					text-align: center;
					font-weight: bold;
				}

				#kanan {
					text-align: right;
				}

				#tengah {
					text-align: center;
				}

			</style>

		<div class="headLap">
			<h3 id="marginLap">CV. Kharisma Tiara Abadi</h3>
			<h3>Laporan Limit Stock Gudang</h3>
		</div>
		<div class="atasTable">
			<p>Batas Limit : '.$batsLimit.' Item</p>
			<p id="daerah">Tanggal Cetak : '.TanggalIndo($today).'</p>
		</div>

';

echo '
			<table width="100%" border="1">
				<thead>
					
					<tr>
						<th>No Urut</th>
						<th>Rak</th>
						<th>Ukuran</th>
						<th>Saldo</th>
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
	         				<td id="kat" colspan="12">'.$val['kat'].'</td>
	         			</tr>';
	    } 
			echo '
					
						<td>'.$no.'</td>
						<td>'.$val['rak'].'</td>
	        			<td>'.$val['brg'].'</td>
	        			<td id="kanan">'.$val['akhir'].'</td>
					</tr>
	        ';


			$no++;

	}
	echo '<tr></tr>';	
}
echo '
				</tbody>
			</table>
';

// $tgl = "2017-07-26";
// $tahun = substr($tgl, 2,2);
// $bulan = substr($tgl,5,2);
// $reg='001';
// echo 'KTA'.$bulan.$tahun.$reg;
?>