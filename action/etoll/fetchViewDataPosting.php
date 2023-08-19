<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';


if ($_POST) {
	$id_post = $koneksi->real_escape_string($_POST['id_post']);

	$query = "SELECT a1.id_toll, id_tmbh, rute, ruteAkhir, bayar, tgl_trans, tmbh_saldo,s_awal
			FROM(	
				SELECT id_toll, rute, no_toll, ruteAkhir, bayar, id_trans, tgl_trans, s_awal, id_post FROM  tblTransToll
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
		<table class="table table-striped table-bordered" id="tabelKeluar">
			<thead>
				
				<tr>
                  <th width="5">No</th>
                  <th width="13%">Saldo Awal</th>
                  <th width="13%">Top UP</th>
                  <th width="22%">Asal Gerbang</th>
                  <th width="21%">Keluar Gerbang</th>
                  <th width="13%">Tanggal</th>
                  <th width="13%">Nominal</th>
				</tr>
			</thead>
			<tbody>';

	$no = 1;
	$bayar = "";
	foreach ($result as $key => $array) {
		
		foreach ($array as $index => $val) {
		echo '	<tr>
				   <td>'.$no.'</td>';

		if ($index==0) {
			echo '<td>'.format_rupiah($val['s_awal']).'</td>';
			echo '<td>'.format_rupiah($val['tmbh_saldo']).'</td>';
		}else{
	    	echo '<td></td>';
	    	echo '<td></td>';			
		}
			echo '<td>'.$val['rute'].'</td>
				  <td>'.$val['ruteAkhir'].'</td>
				  <td>'.TanggalIndo($val['tgl_trans']).'</td>
				  <td id="kanan">'.format_rupiah($val['bayar']).'</td>
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
              <th colspan="6" style="text-align: right;">Saldo Awal : </th>
              <th>'.format_rupiah($sAwal).'</th>
            </tr>
            <tr>
              <th colspan="6" style="text-align: right;">Total Pemakaian : </th>
              <th>'.format_rupiah($bayar).'</th>
            </tr>
            <tr>
              <th colspan="6" style="text-align: right;">Saldo Akhir : </th>
              <th>'.format_rupiah($sAwal-$bayar).'</th>
            </tr>
          </tfoot>		
		</table>';

}

?>