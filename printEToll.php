<?php
require_once 'function/koneksi.php';
require_once 'function/setjam.php';
require_once 'function/session.php';
require_once 'function/tgl_indo.php';
require_once 'function/fungsi_rupiah.php';

//if ($_POST) {
//$id_toll = $koneksi->real_escape_string($_POST['noEToll']);
//$bulan   = $koneksi->real_escape_string($_POST['bulan']);
//$tahun   = $koneksi->real_escape_string($_POST['tahun']);
$id_toll = 2;
$bulan = 9;
$tahun = 2017;
$today   = date("Y-m-d");

	$sql = "SELECT id_toll, no_toll, rute, tgl_trans, ket, SUM(IFNULL(bayar, 0)) AS total 
FROM tblDetTransToll
	JOIN tblTransToll USING(id_trans)
	RIGHT JOIN tblEToll USING(id_toll)
	WHERE MONTH(tgl_trans) = $bulan AND YEAR(tgl_trans) = $tahun AND id_toll = $id_toll GROUP BY id_DetTrans

UNION

	SELECT CONCAT(id_toll, '1SUB TOTAL'), NULL, NULL, NULL, NULL, SUM(IFNULL(bayar, 0)) AS totalBayar FROM tblDetTransToll
	JOIN tblTransToll USING(id_trans)
	WHERE MONTH(tgl_trans) = $bulan AND YEAR(tgl_trans) = $tahun AND id_toll = $id_toll GROUP BY id_toll

UNION

SELECT CONCAT(a1.id_toll, '4SALDO AKHIR'),NULL, NULL, NULL, NULL, saldoTambah-saldoKurang
FROM(
	SELECT id_toll, no_toll, saldo, SUM(IFNULL(bayar, 0)) AS saldoKurang FROM tblDetTransToll
	JOIN tblTransToll USING(id_trans)
	JOIN tblEToll USING(id_toll)
	WHERE id_toll = $id_toll GROUP BY id_toll
) a1
LEFT JOIN(
	SELECT id_toll, saldo+SUM(IFNULL(tmbh_saldo, 0)) AS saldoTambah FROM tblTmbhSaldo
	RIGHT JOIN tblEToll USING(id_toll)
	GROUP BY no_toll
)b1 ON a1.id_toll=b1.id_toll
RIGHT JOIN(
SELECT id_toll
FROM tblTransToll
	RIGHT JOIN tblDetTransToll USING(id_trans)
	WHERE MONTH(tgl_trans) = $bulan AND YEAR(tgl_trans) = $tahun AND id_toll = $id_toll GROUP BY id_toll
)c1 ON b1.id_toll=c1.id_toll

UNION 

SELECT CONCAT(a2.id_toll, '2SALDO AWAL'),NULL, NULL, NULL, NULL, IFNULL(saldoTambah-saldoKurang, 0)
FROM(
	SELECT id_toll, no_toll, saldo, tgl_trans, SUM(IFNULL(bayar, 0)) AS saldoKurang FROM tblDetTransToll
	JOIN tblTransToll USING(id_trans)
	RIGHT JOIN tblEToll USING(id_toll)
	WHERE MONTH(tgl_trans) = $bulan AND YEAR(tgl_trans) = $tahun AND id_toll = $id_toll GROUP BY id_toll
) a2
LEFT JOIN(
	SELECT id_toll, tgl_tmbh, saldo+SUM(IFNULL(tmbh_saldo, 0)) AS saldoTambah FROM tblTmbhSaldo
	RIGHT JOIN tblEToll USING(id_toll)
	WHERE MONTH(tgl_tmbh) < $bulan AND YEAR(tgl_tmbh) <= $tahun GROUP BY id_toll
)b2 ON a2.id_toll=b2.id_toll
RIGHT JOIN(
SELECT id_toll
FROM tblTransToll
	RIGHT JOIN tblDetTransToll USING(id_trans)
	WHERE MONTH(tgl_trans) = $bulan AND YEAR(tgl_trans) = $tahun AND id_toll = $id_toll GROUP BY id_toll
)c2 ON b2.id_toll=c2.id_toll

UNION

SELECT CONCAT(id_toll, '3SALDO MASUK'),NULL, NULL, NULL, NULL, SUM( IFNULL(tmbh_saldo, 0)) FROM tblTmbhSaldo
	RIGHT JOIN tblEToll USING(id_toll)
	WHERE MONTH(tgl_tmbh) = $bulan AND YEAR(tgl_tmbh) = $tahun AND id_toll = $id_toll GROUP BY id_tmbh

ORDER BY id_toll, no_toll ASC, no_toll ASC";
	$result = $koneksi->query($sql);
	echo '
				<style>
					body {
						font-family:tahoma, arial
					}
					table {
						border-collapse: collapse
					}
					th, td {
						font-size: 13px; 
						border: 1px solid #DEDEDE; 
						padding: 5px 7px; 
						color: #303030
					}
					th {
						background: #EAEAEA; 
						font-size: 13px; 
						border-color:#B0B0B0
					}
					.subtotal td{
						background: #F5F5F5; 
						font-weight:bold;
					}
					.total td{
						background: #ECECEC
					}
					.right{
						text-align: right
					}
					.tdkada{
						display:none;
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
					    margin-bottom: -10px; 
					}
					.ttd{
						padding-bottom: 73px;
					    font-size: 13px;
					    text-align: center;
					    font-weight: bold;
					}
				</style>

				<div class="headLap">
					<h3 id="marginLap">CV. Kharisma Tiara Abadi</h3>
					<h3>Laporan Transaksi E-TOLL Bulan September Tahun 2017</h3>
				</div>

				<div class="atasTable">
					<p>Tanggal Cetak : '.TanggalIndo($today).'</p>
				</div>
				';



	echo '<table class="grey" width="100%" border="1">
			<thead>
				<tr>
					<th width="16%"></th>
					<th>NO E-TOLL</th>
					<th>RUTE</th>
					<th>TANGGAL</th>
					<th>KETERANGAN</th>
					<th>TOTAL</th>
				</tr>
			</thead>
			<tbody>';


	while ($row = $result->fetch_assoc())
	{
			
			$id_pel = $row['no_toll'];
			$tahun = $row['id_toll'];
			$rute = $row['rute'];
			$id_pro = $row['tgl_trans'];
			$ket = $row['ket'];
			$total = number_format($row['total'], '0', ',', '.');

		echo  
		'<tr>
			
			<td>' . $tahun . '</td>
			<td>' . $id_pel . '</td>
			<td>' . $rute . '</td>
			<td>' . $id_pro . '</td>
			<td>' . $ket . '</td>
			<td class="right">' . $total . '</td>
		</tr>';

	}

	echo '
		</tbody>
	</table>

	<div ttd1>
		<p>PENGIRIM</p>
		<p>MENYETUJUI</p>
	</div>

	';



//}

?>