<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';


if ($_POST) {
	$id_post = $koneksi->real_escape_string($_POST['id_post']);
	//$id_post = 1;
	$query = "SELECT a1.id_toll, id_tmbh, no_toll, pemegang, no_pol, rute, ruteAkhir, bayar, tgl_trans, tmbh_saldo,s_awal
			FROM(	
				SELECT id_toll, rute, no_toll, pemegang, no_pol, ruteAkhir, bayar, id_trans, tgl_trans, s_awal, id_post FROM  tblTransToll
				JOIN tblDetTransToll USING(id_trans)
				JOIN tblEToll USING(id_toll)
				JOIN tblPostingEToll AS post USING(id_trans) WHERE id_post = $id_post
			)a1
			LEFT JOIN (
				SELECT id_toll, id_tmbh, tmbh_saldo, id_post FROM tblTmbhSaldo
				JOIN tblPostingEToll USING(id_tmbh) WHERE id_post = $id_post
				
			)a2 ON a1.id_toll=a2.id_toll ";

	$rest = $koneksi->query($query);

	$fetch = $rest->fetch_all(MYSQL_ASSOC); 

	foreach ($fetch as $key => $val) {
		$result[$val['id_tmbh']][] = $val;
	}


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
                  <th rowspan="2" width="5">No</th>
                  <th rowspan="2" width="10%">Saldo Awal</th>
                  <th rowspan="2" width="10%">Top UP</th>
                  <th rowspan="2" >Asal Gerbang</th>
                  <th rowspan="2" >Keluar Gerbang</th>
                  <th rowspan="2" width="10%">Tanggal</th>
                  <th rowspan="2" width="10%">Nominal</th>
                  <th width="17%" colspan="7">PERSENTASE</th>
				</tr>
				<tr>
				  <th>IRC</th>
				  <th>GT</th>
				  <th>ZN</th>
				  <th>KN</th>
				  <th>GY</th>
				  <th>SW</th>
				  <th>KR</th>
				</tr>
			</thead>
			<tbody>';

	$no = 1;
	$bayar = "";
	foreach ($result as $key => $array) {
		
		foreach ($array as $index => $val) {
		echo '	<tr>
				   <td class="tengah">'.$no.'</td>';

		if ($index==0) {
			echo '<td class="kanan padding">Rp. '.format_rupiah($val['s_awal']).'</td>';
			echo '<td class="kanan padding">Rp.  '.format_rupiah($val['tmbh_saldo']).'</td>';
		}else{
	    	echo '<td></td>';
	    	echo '<td></td>';			
		}
			echo '<td class="padding"> '.$val['rute'].'</td>
				  <td class="padding"> '.$val['ruteAkhir'].'</td>
				  <td class="tengah padding" >'.TanggalIndo($val['tgl_trans']).'</td>
				  <td class="kanan padding">Rp. '.format_rupiah($val['bayar']).'</td>
				  <td></td>
				  <td></td>
				  <td></td>
				  <td></td>
				  <td></td>
				  <td></td>
				  <td></td>
				</tr>';

		$no++;
		$bayar +=$val['bayar'];

		}
	}
$sAwal =  $val['s_awal'] + $val['tmbh_saldo'];
echo ' 
		</tbody>
          <tfoot>
            <tr>
              <th class="kanan padding-total" colspan="6">Saldo Awal : </th>
              <th class="kanan padding-total">Rp. '.format_rupiah($sAwal).' </th>
              <th colspan="7" class="atas-padding" rowspan="4"> KETERANGAN : </th>
            </tr>
            <tr>
              <th class="kanan padding-total" colspan="6">Total Pemakaian : </th>
              <th class="kanan padding-total">Rp. '.format_rupiah($bayar).' </th>
            </tr>
            <tr>
              <th class="kanan padding-total" colspan="6">Saldo Akhir : </th>
              <th class="kanan padding-total">Rp. '.format_rupiah($sAwal-$bayar).' </th>
            </tr>
          </tfoot>		
		</table>
		<table width="100%" >
		  <thead>
			<tr>
			  <th class="batas-atas">MENGETAHUI</th>
			  <th class="batas-atas">MENYETUJUI</th>
			</tr>
			<tr>
				<th class="batas-atas1">(________________________)</th>
				<th class="batas-atas1">(________________________)</th>
			</tr>
		  </thead>
		</table>';

}

?>