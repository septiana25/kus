<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';


if ($_POST) {
	$noEToll = $koneksi->real_escape_string($_POST['noEToll']);
	//$noEToll = 1;

	$querTmbh = "SELECT SUM(tmbh_saldo) AS tmbh FROM tblTmbhSaldo WHERE id_toll = $noEToll";

	$res = $koneksi->query($querTmbh);
	$row1 = $res->fetch_assoc();

	$query = "SELECT id_toll, rute, no_toll, pemegang, no_pol, ruteAkhir, bayar, id_trans, tgl_trans, ket FROM  tblTransToll
				JOIN tblDetTransToll USING(id_trans)
				JOIN tblEToll USING(id_toll) WHERE id_toll = $noEToll";

	$rest = $koneksi->query($query);

	$fetch = $rest->fetch_all(MYSQL_ASSOC); 

	foreach ($fetch as $key => $val) {
		$result[$val['id_trans']][] = $val;
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
		<table class="table2">
			<thead >
				<tr>
				  <th class="padding-head">NO KARTU FLASH</th>
				  <th> : '.$val['no_toll'].'</th>
				</tr>
				<tr>
				  <th class="padding-head">NO POLISI</th>
				  <th> : '.$val['no_pol'].'</th>
				</tr>
				<tr>
				  <th class="padding-head">PEMEGANG KARTU </th>
				  <th class="padding-head"> : '.$val['pemegang'].'</th>
				</tr>
			</thead>
		</table>

		<table border="1" width="100%" class="table">
			<thead>
				
				<tr>
                  <th width="5">No</th>
                  <th width="10%">Top UP</th>
                  <th >Asal Gerbang</th>
                  <th >Keluar Gerbang</th>
                  <th width="10%">Tanggal</th>
                  <th width="10%">Nominal</th>
                  <th>Ket</th>
				</tr>
			<tbody>';

	$no = 1;
	//$no1 = "";
	$bayar = "";
	foreach ($result as $key => $array) {
		//$no1 = 1;
		foreach ($array as $index => $val) {
		echo '	<tr>
				   <td class="tengah">'.$no.'</td>';

		if ($index==0) {
			//echo '<td class="kanan padding">Rp. '.format_rupiah($val['id_trans']).'</td>';
echo '<td></td>';

		}else{
	    	echo '<td></td>';			
		}
			if ($val['ket'] == '-') {
				$kat = '';
			}else {
				$kat = $val['ket'];
			}
			echo '<td class="padding"> '.$val['rute'].'</td>
				  <td class="padding"> '.$val['ruteAkhir'].'</td>
				  <td class="tengah padding" >'.TanggalIndo($val['tgl_trans']).'</td>
				  <td class="kanan padding">Rp. '.format_rupiah($val['bayar']).'</td>
				  <td class="padding">'.$kat.'</td>
				</tr>';
		//$no1++;
		$no++;
		$bayar +=$val['bayar'];

		}
	}
//$sAwal =  $val['s_awal'] + $val['tmbh_saldo'];
echo ' 
		</tbody>
          <tfoot>
            <tr>
              <th class="kanan padding-total" colspan="5">Total TOP UP : </th>
              <th class="kanan padding-total">Rp. '.format_rupiah($row1['tmbh']).' </th>
              <th rowspan="3"></th>
            </tr>
            <tr>
              <th class="kanan padding-total" colspan="5">Total Pemakaian : </th>
              <th class="kanan padding-total">Rp. '.format_rupiah($bayar).' </th>
            </tr>
            <tr>
              <th class="kanan padding-total" colspan="5">Saldo Akhir : </th>
              <th class="kanan padding-total">Rp. '.format_rupiah($row1['tmbh']-$bayar).' </th>
            </tr>
          </tfoot>		
		</table>';

}

?>