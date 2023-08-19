<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';

if ($_POST) {
//date('Y-m-d', strtotime('-6 days', strtotime( variabel_tgl_awal ))); //kurang tanggal sebanyak 6 hari

	$tglAwalRtr = $koneksi->real_escape_string($_POST['tglAwalRtr']);
	$tglAkhirRtr = $koneksi->real_escape_string($_POST['tglAkhirRtr']);

	$query = "SELECT tgl_msk, toko, a.suratJln, no_faktur, brg, rak, jml_msk, total, ket, rowspan
				FROM (
				SELECT msk.tgl AS tgl_msk, IFNULL(toko, toko1) AS toko, suratJln, no_faktur, brg, rak.rak AS rak, jml_msk,
				detMsk.ket AS ket
				FROM detail_masuk AS detMsk
				JOIN masuk AS msk USING(id_msk)
				JOIN detail_brg AS detBrg USING(id)
				JOIN barang USING(id_brg)
				JOIN rak USING(id_rak)
				LEFT JOIN keluar AS klr USING(no_faktur)
				LEFT JOIN toko ON klr.id_toko=toko.id_toko
				WHERE retur= '1' AND msk.tgl BETWEEN '$tglAwalRtr' AND '$tglAkhirRtr' ORDER BY msk.tgl ASC
				) AS a
				LEFT JOIN (
				SELECT suratJln, COUNT(suratJln) AS rowspan, SUM(jml_msk) AS total
				FROM detail_masuk
				JOIN masuk USING(id_msk)
				WHERE retur= '1'
				GROUP BY suratJln
				) AS b USING(suratJln) ORDER BY tgl_msk DESC";

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
					<th style="color:red;">LAPORAN AKTIVITAS RETUR BARANG</th>
				</tr>
			</thead>
		</table>
		<table class="table2">
			<thead >
				<tr>
				  <th class="tengah">Periode: '.TanggalIndo($tglAwalRtr).' - '.TanggalIndo($tglAkhirRtr).'</th>
				</tr>
			</thead>
		</table>
		<table border="1" width="100%" class="table">
			<thead>
				<tr>
                  <th width="5%">No</th>
                  <th width="10%">Tanggal</th>
                  <th >Nama Toko</th>
                  <th >No.Retur</th>
                  <th >Dari Faktur</th>
                  <th >Nama Barang</th>
                  <th >Ket</th>
                  <th >Lokasi</th>
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
		
echo '		<tr>';

		if ($index==0)
		{

echo '			<td rowspan="'.$val['rowspan'].'" class="tengah padding">'.$no.'</td>';
		}

echo '			<td  class="tengah padding">'.TanggalIndo($val['tgl_msk']).'</td>';

		if ($index==0)
		{

echo '            <td rowspan="'.$val['rowspan'].'" class="padding">'.$val['toko'].'</td>
                  <td rowspan="'.$val['rowspan'].'" class="tengah padding">'.$val['suratJln'].'</td>
                  <td rowspan="'.$val['rowspan'].'" class="tengah padding">'.$val['no_faktur'].'</td>
';
			$no++;
		}

echo '                  
                  <td class="padding">'.$val['brg'].'</td>
                  <td class="padding">'.$val['ket'].'</td>
                  <td class="padding">'.$val['rak'].'</td>
                  <td class="tengah padding">'.$val['jml_msk'].'</td>
				
';
		if ($index==0) {
echo '
                  <td rowspan="'.$val['rowspan'].'" class="tengah padding">'.$val['total'].'</td>

';
		}
			//$total++;
echo '		</tr>';
		

	}
}

/*	$no         = 1;

		while ($row = $rest->fetch_assoc()) {

			echo '<td class="padding"> '.TanggalHuruf($row['tgl_tmbh']).'</td>
				  <td class="tengah padding"> '.$no.'</td>
				  <td class="tengah padding"> '.$row1['no_pol'].'</td>
				  <td class="kanan padding">Rp. '.format_rupiah($row['tmbh_saldo']).'</td>
				  <td class="kanan padding">Rp. '.format_rupiah($row['SaldoAwal']).'</td>
				  <td class="kanan padding">Rp. '.format_rupiah($row['SaldoAkhir']).'</td>
				</tr>';

		$no++;
			
		}*/



//$sAwal =  $val['s_awal'] + $val['tmbh_saldo'];
/*echo '
          <tfoot>
            <tr>
              <th class="tengah padding-total" colspan="3">Total Pengisian : </th>
              <th class="kanan padding-total">Rp. '.format_rupiah($totalTmbh).' </th>
              <th class="kanan padding-total">Rp. '.format_rupiah($totalAwal).' </th>
              <th class="kanan padding-total">Rp. '.format_rupiah($totalAkhir).' </th>
            </tr>

          </tfoot>
		</tbody>		
		</table>';*/
echo '</tbody></table>';
}
else
{
	echo "Laporan Yang Anda Minta Tidak Anda";
}

$koneksi->close();

}

?>