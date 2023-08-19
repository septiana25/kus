<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';

//$kal       = CAL_GREGORIAN;
// $bulan     = $_POST['bulan'];
// $tahun     = $_POST['tahun'];
//$hari      = cal_days_in_month($kal, $bulan, $tahun);

// $bulan     = 7;
// $tahun     = 2017;

// $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

$getFaktur = "SELECT SUBSTRING(no_faktur, 7) AS akhrFaktur FROM keluar WHERE SUBSTRING(no_faktur, -13, 2)!='MG' ORDER BY no_faktur DESC LIMIT 0,1";
$resGetFaktur = $koneksi->query($getFaktur);
$rowGet = $resGetFaktur->fetch_array();
$akhrFaktur = $rowGet['akhrFaktur'];

$cekFaktur="SELECT COUNT(no_faktur) AS countFaktur FROM keluar WHERE SUBSTRING(no_faktur, -11, 6)='17.000'";
$resFaktur = $koneksi->query($cekFaktur);
$rowFaktur = $resFaktur->fetch_assoc();
$countFaktur = $rowFaktur['countFaktur'];

if ($countFaktur == (int)$akhrFaktur) {
	$valid = "No Faktur Lengkap";
}else{

	$valid = "No Faktur Ada Yang Kelewat";
}

	// if ($_POST) {
		$query = "SELECT no_faktur AS faktur, tgl, pengirim, ket FROM keluar 
				  WHERE SUBSTRING(no_faktur, -13, 2)!='MG' AND no_faktur !=0 ORDER BY faktur ASC";
		$result = $koneksi->query($query);

		$fetch = $result->fetch_all(MYSQL_ASSOC);

		if ($result->num_rows > 0) {
	// echo "<pre>". print_r($fetch); die;

	// echo '<pre>'.print_r($result1, true).'</pre>';

	echo '

		<html>
			<head>
			<title>Lapran Claim </title>
				<style>
					body {font-family:"segoe ui", "open sans", tahoma, arial}
					table {border-collapse: collapse}
		
					
					.total td {background-color: #f5f5f5 !important;}
					.right{text-align: right}
					table tr:nth-child(odd) td {
						background-color: #fbfbfb;
						border-bottom: 1px solid #efefef;
						border-top: 1px solid #ececec;
					}
					table th {
						color: #616161;
						margin: 0;
						padding: 10px 10px;
						border: 1px solid #e4e4e4;
						text-align: center;
						font-size: 13.5px;
						
						background: #efefef;

					}
					table td {
						border-right: 1px solid #ececec;
						border-left: 1px solid #ececec;
						padding: 7px 15px;
						color: #676767;
						font-size: 13px;
					}
					/*table td:nth-child(n+3) {
						text-align: right;
					}*/
					td#kategori 
					{
					    background: #676767;
					    color: white;
					    text-align: center;
					    font-weight: bold;
					}

					.headLap{
						text-align: center;
						text-transform: uppercase;
						color: #676767;
					}
					.headLap #marginLap 
					{
						margin-bottom: -15px;
					}
					
					.atasTable{
						font-size: 13px;
					    color: #676767;
					    font-weight: bold;
					}

					.atasTable p 
					{
					    float: left;
					    margin-left: 7px;
					    margin-bottom: 5px;
					}

					p#daerah 
					{
					    margin-left: 152px;
					}

					p#tgl 
					{
					    margin-left: 450px;
					}

					.kanan 
					{
						text-align: right;
					}

					#note{
						color: #676767;
					}

				</style>
			</head>
			<body>


	';

	echo '
			<div class="headLap">
				<h3 id="marginLap">CV. Kharisma Tiara Abadi</h3>
				<h3>Laporan Perfaktur</h3>
			</div>
			<div class="atasTable">

			</div>
			<div id="note">
			*Note : '.$valid.'
			</div>
	';

	echo '
				<table width="100%">
					<thead>
						
						<tr>
							<th >No</th>
							<th>No Faktur</th>
							<th>Tanggal Kirim</th>
							<th>Pengirim</th>
							<th>Ket</th>
						</tr>
					</thead>
					<tbody>

	';
	$no = "";

		$no=1;
		foreach ($fetch as $key => $val) { 
			echo '	<tr>	
						<td>'.$no.'</td>
						<td>'.$val['faktur'].'</td>
						<td>'.TanggalIndo($val['tgl']).'</td>
						<td>'.$val['pengirim'].'</td>
						<td>'.$val['ket'].'</td>
					</tr>';
			$no++;
		}
		echo '<tr></tr>';
	echo '
					</tbody>
				</table>
			</body>
		</html>
	';
	}else{
		echo "No Faktur Tidak Ada";
	}



?>
