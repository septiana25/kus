<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';

$kal       = CAL_GREGORIAN;
// $bulan     = $_POST['bulan'];
// $tahun     = $_POST['tahun'];
//$hari      = cal_days_in_month($kal, $bulan, $tahun);

$bulan     = 7;
$tahun     = 2017;

$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");


// if ($_POST) {
	$query = "SELECT SUBSTRING(no_faktur,-5) AS faktur, tgl, pengirim FROM keluar 
			  WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun AND SUBSTRING(no_faktur, -13, 2)!='MG' ORDER BY faktur ASC";
	$result = $koneksi->query($query);
	$array = array();
	while ($row = $result->fetch_array()) {
		array_push($array, $row);
	}
	$table='<h3 style="text-align:center;">Laporan Faktur '.$BulanIndo[(int)$bulan-1].'</h3>';
	$table.='Dicetak oleh '.$_SESSION['nama'];
	$table .= '<table border="1" cellspacing="0" cellpadding="0" style="width:100%;">
				  <tr>
					<td><center>Faktur</center></td>
					<td><center>Tgl Kirim</center></td>
					<td><center>Pengirim</center></td>
					<td>aaa</td>
					<td><center>Faktur</center></td>
					<td><center>Tgl Kirim</center></td>
					<td><center>Pengirim</center></td>
					<td>aaa</td>
					<td><center>Faktur</center></td>
					<td><center>Tgl Kirim</center></td>
					<td><center>Pengirim</center></td>
			  ';
	$kolom = 3;
	$chunks = array_chunk($array, $kolom);
	foreach ($chunks as $chunk) {
		$table .='<tr>';
		foreach ($chunk as $key) {
			$table .='
					  	<td>'.$key['faktur'].'</td>
					  	<td>'.TanggalIndo($key['tgl']).'</td>
					  	<td>'.$key['pengirim'].'</td><td></td>';
		}
		$table .='</tr>';
	}

	$table .= '
				</tr>
			</table>';
	echo $table;
// }

?>