<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';

if ($_POST) {

$id_toll = $koneksi->real_escape_string($_POST['postNoEtoll']);

	$query = "SELECT a1.id_toll, no_toll, totalTmbh-totalTrans as saldoAwal, id_tmbh, tmbh_saldo, rute, ruteAkhir, bayar, id_trans, tgl_trans
		FROM(	
			SELECT id_toll, rute, no_toll, ruteAkhir, bayar, id_trans, tgl_trans FROM  tblTransToll
			JOIN tblDetTransToll USING(id_trans)
			JOIN tblEToll USING(id_toll)
			WHERE stus_trans = 0  AND id_toll = $id_toll
		)a1
		LEFT JOIN (
			SELECT id_toll, id_tmbh, tmbh_saldo FROM tblTmbhSaldo
			WHERE stus_tmbh = 0 AND id_toll = $id_toll
		)a2 ON a1.id_toll=a2.id_toll

		LEFT JOIN (
		SELECT id_toll,  SUM(IFNULL(bayar, 0)) AS totalTrans FROM  tblTransToll
			JOIN tblDetTransToll USING(id_trans)
			WHERE stus_trans = 1 AND id_toll = $id_toll
		) a3 LEFT JOIN(
		SELECT id_toll, SUM(IFNULL(tmbh_saldo, 0)) AS totalTmbh
			FROM tblTmbhSaldo
			WHERE stus_tmbh = 1 AND id_toll = $id_toll
		) a4 ON a3.id_toll=a4.id_toll ON a1.id_toll=a2.id_toll";

$result1 = $koneksi->query($query);
$fetch = $result1->fetch_all(MYSQL_ASSOC);

// echo "<pre>". print_r($fetch); die;
foreach ($fetch as $key => $val) 
{
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
$tmbh = "";
foreach ($result as $kat => $array)
{
	foreach ($array as $index => $val) {

			echo '<tr>
					
						<td>'.$no.'</td>';
        if ($index==0) 
        {
        	echo '<td>'.format_rupiah($val['saldoAwal']).'</td>';
         	echo '<td>'.format_rupiah($val['tmbh_saldo']).'</td>';
         	echo '<input type="hidden" name="id_trans" value="'.$val['id_trans'].'"/>';
         	echo '<input type="hidden" name="id_tmbh" value="'.$val['id_tmbh'].'"/>';
         	echo '<input type="hidden" name="saldoAwal" value="'.$val['saldoAwal'].'"/>';
        	$tmbh += $val['tmbh_saldo']; 
	    }else{
	    	echo '<td></td>';
	    	echo '<td></td>';
	    }
			echo'		
						
						<td>'.$val['rute'].'</td>
						<td>'.$val['ruteAkhir'].'</td>
						<td>'.TanggalIndo($val['tgl_trans']).'</td>
	        			<td id="kanan">'.format_rupiah($val['bayar']).'</td>
					</tr>

	        ';
	        $bayar +=$val['bayar'];


			$no++;

	}

}
$sAwal =  $val['saldoAwal'] + $val['tmbh_saldo'];

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