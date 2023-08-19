<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';

if ($_POST) {
//date('Y-m-d', strtotime('-6 days', strtotime( variabel_tgl_awal ))); //kurang tanggal sebanyak 6 hari

	$tglAwalMTS  = $koneksi->real_escape_string($_POST['tglAwalMTS']);
	$tglAkhirMTS = $koneksi->real_escape_string($_POST['tglAkhirMTS']);

	$query = "SELECT a.tgl, brg, asalRak, rak, suratJln, jml_msk, total, rowspan
			FROM (
			SELECT tgl, brg, detMsk.rak AS asalRak, rak.rak AS rak, suratJln, jml_msk
			FROM detail_masuk AS detMsk
			JOIN masuk AS msk USING(id_msk)
			JOIN detail_brg USING(id)
			JOIN barang USING(id_brg)
			JOIN rak USING(id_rak)
			WHERE retur = '3' AND msk.tgl BETWEEN '$tglAwalMTS' AND '$tglAkhirMTS' ORDER BY tgl ASC
			) AS a
			LEFT JOIN (
			SELECT suratJln, COUNT(suratJln) AS rowspan, SUM(jml_msk) AS total
			FROM detail_masuk
			JOIN masuk AS msk1 USING(id_msk)
			WHERE retur= '3' AND msk1.tgl BETWEEN '$tglAwalMTS' AND '$tglAkhirMTS'
			GROUP BY suratJln
			) AS b USING(suratJln) ORDER BY tgl DESC";

	$rest = $koneksi->query($query);

		
if ($rest->num_rows > 0)
{


$fetch = $rest->fetch_all(MYSQL_ASSOC);

//echo "<pre>". print_r($fetch); die;
foreach ($fetch as $key => $val) 
{
  $result[$val['suratJln']][] = $val;

}
//echo '<pre>'.print_r($result, true).'</pre>';


echo '
		<style>
		body {
			font-family:"segoe ui", "open sans", tahoma, arial;
		}
		table {
			border-collapse: collapse;
		}
		.tengah {
			text-align: center;
		}
		.kanan {
			text-align: right;
		}
		.table tr:nth-child(odd) th {
			background-color: #fbfbfb;
			border-bottom: 1px solid #585656;
			border-top: 1px solid #5a5353;
		}
		.table th {
			text-transform: uppercase;
		}
		.padding {
			padding: 0 5px 0 5px;
		}
		.padding-total{
			padding: 5px 5px 5px 0;
		}
		.batas-atas {
			padding-top : 5px;
		}

		.batas-atas1 {
			padding-top : 50px;
		}
		.table2 {
			text-align: left;
		}
		.padding-head {
			padding: 2px 5px 0 0;
		}
		.atas-padding {
			padding-bottom : 62px;
		}

		</style>
';


echo '
		<table width="100%">
			<thead>
				<tr>
					<th style="color:red;">LAPORAN AKTIVITAS MUTASI BARANG</th>
				</tr>
			</thead>
		</table>
		<table class="table2">
			<thead >
				<tr>
				  <th class="tengah">Periode: '.TanggalIndo($tglAwalMTS).' - '.TanggalIndo($tglAkhirMTS).'</th>
				</tr>
			</thead>
		</table>

		<table border="1" width="100%" class="table">
			<thead>
				<tr>
                  <th width="5%">No</th>
                  <th width="10%">Tanggal</th>
                  <th >No Mutasi</th>
                  <th >Barang</th>
                  <th >Asal Rak</th>
                  <th >Lokasi Rak</th>
                  <th width="5%">QTY</th>
                  <th width="7%">Total</th>
				</tr>
			</thead>
			<tbody>';

$no=1;

foreach ($result as $kat => $array)
{

	foreach ($array as $index => $val)
	{
		
echo '			<tr>';
		if ($index==0)
		{

echo '
                  <td rowspan="'.$val['rowspan'].'" class="tengah padding">'.$no.'</td>';
			
		}
echo '            <td class="tengah padding">'.TanggalIndo($val['tgl']).'</td>';

		if ($index==0)
		{
echo '			  <td rowspan="'.$val['rowspan'].'" class="tengah padding">'.$val['suratJln'].'</td>';
		}


echo '     
                  <td class="padding">'.$val['brg'].'</td>
                  <td class="padding">'.$val['asalRak'].'</td>
                  <td class="padding">'.$val['rak'].'</td>
                  <td class="tengah padding">'.$val['jml_msk'].'</td>
				
';


		if ($index==0)
		{
echo '			  <td rowspan="'.$val['rowspan'].'" class="tengah padding">'.$val['total'].'</td>';
				  $no++;
		}
			//$total++;

echo '			</tr>';		

	}
}

echo '</tbody></table>';
}
else
{
	echo "Laporan Yang Anda Minta Tidak Anda";
}

$koneksi->close();

}

?>
