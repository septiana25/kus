<?php

require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
require_once 'function/tgl_indo.php';
require_once 'function/fungsi_rupiah.php';

/*SELECT a1.id_toll, id_tmbh, tmbh_saldo, rute, ruteAkhir, bayar, id_trans
FROM(	
	SELECT id_toll, rute, ruteAkhir, bayar, id_trans FROM  tblTransToll
	JOIN tblDetTransToll USING(id_trans)
	WHERE stus = 0 AND bayar !=0 AND id_toll = 3
)a1
LEFT JOIN (
	SELECT id_toll, id_tmbh, tmbh_saldo FROM tblTmbhSaldo
	WHERE stus = 0 AND tmbh_saldo !=0 AND id_toll = 3
)a2 ON a1.id_toll=a2.id_toll*/

// $bulan     = $_POST['b'];
// $tahun     = $_POST['t'];
$bulan     = 8;
$tahun     = 2017;

$lapClaim = "SELECT a1.id_toll,totalTmbh, totalTmbh-totalTrans as saldoAwal, id_tmbh, tmbh_saldo, rute, ruteAkhir, bayar, id_trans
FROM(	
	SELECT id_toll, rute, ruteAkhir, bayar, id_trans FROM  tblTransToll
	JOIN tblDetTransToll USING(id_trans)
	WHERE bayar !=0 AND id_toll = 3
)a1
LEFT JOIN (
	SELECT id_toll, id_tmbh, tmbh_saldo FROM tblTmbhSaldo
	WHERE tmbh_saldo !=0 AND id_toll = 3
)a2 ON a1.id_toll=a2.id_toll

LEFT JOIN (
SELECT id_toll,  SUM(IFNULL(bayar, 0)) AS totalTrans FROM  tblTransToll
	JOIN tblDetTransToll USING(id_trans)
	WHERE id_toll = 3
) a3 LEFT JOIN(
SELECT id_toll, SUM(IFNULL(tmbh_saldo, 0)) AS totalTmbh
	FROM tblTmbhSaldo
	WHERE id_toll = 3
) a4 ON a3.id_toll=a4.id_toll ON a1.id_toll=a2.id_toll";

$result1 = $koneksi->query($lapClaim);
$fetch = $result1->fetch_all(MYSQL_ASSOC);

// echo "<pre>". print_r($fetch); die;
foreach ($fetch as $key => $val) 
{
  $result[$val['id_tmbh']][] = $val;

}
echo '<pre>'.print_r($result, true).'</pre>';

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
					color: white;
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
					padding-top: 20px;
				}

				#kanan {
					text-align: right;
				}

				#tengah {
					text-align: center;
				}
				.tebal {
					font-weight: bold;
				}
				.control-label {
					text-align: left;
					width: 300px;
				}

				label{
					display: block;
					margin-bottom: -10px; 
					font-size: 14px;
					font-weight: normal;
					line-height: 20px;
				}

				strong{
					font-weight: normal;
				}

				.titik2 {
					padding-left: 130px;
					margin-top: -20px;
					font-weight: bold;
				}

				#nota th, td{
					padding: 1px;
					margin: 1px;
					font-size: 14px;
				}

			</style>

';

echo '

			<label class="control-label">
				<strong>No Polisi</strong>
				<p class="titik2">:  D 234 AZ</p>
			</label>

			<label class="control-label">
				<strong>No Flash</strong>
				<p class="titik2"> : '.$val['no_toll'].'</p>
			</label>
			<table width="100%" border="1">
				<thead>
					
					<tr>
						<th>No</th>
						<th>Saldo Awal</th>
						<th>Top UP</th>
						<th>Rute</th>
						<th>Tanggal</th>
						<th>Bayar</th>
					</tr>
				</thead>
				<tbody>';

$no = 1;
$bayar = "";
foreach ($result as $kat => $array)
{
	foreach ($array as $index => $val) {

			echo '<tr>
					
						<td>'.$no.'</td>';
        if ($index==0) 
        {
        	echo '<td>'.$val['saldoAwal'].'</td>';
         	echo '<td>'.format_rupiah($val['tmbh_saldo']).'</td>';
	    }else{
	    	echo '<td></td>';
	    	echo '<td></td>';
	    }
			echo'		
						
						<td>'.$val['rute'].'</td>
	        			<td>'.$val['tgl_trans'].'</td>
	        			<td id="kanan">'.format_rupiah($val['bayar']).'</td>
					</tr>

	        ';
	        $bayar +=$val['bayar'];


			$no++;

	}
	echo '<tr></tr>';
}
$sAwal = $val['saldoAwal'] + $val['tmbh_saldo'];
echo '			<tr>
					<td id="tengah" class="tebal" colspan="5">SUB TOTAL</td>
					<td id="kanan" class="tebal">'.format_rupiah($bayar).'</td>
				</tr>
				<tr>
					<td id="tengah" class="tebal" colspan="5">SALDO AWAL</td>
					<td id="kanan" class="tebal">'.format_rupiah($val['totalTmbh']).'</td>
				</tr>
				<tr>
					<td id="tengah" class="tebal" colspan="5">SALDO AKHIR</td>
					<td id="kanan" class="tebal">'.format_rupiah($sAwal - $bayar).'</td>
				</tr>
				</tbody>
			</table>';

