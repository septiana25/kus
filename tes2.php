<?php
// $kal       = CAL_GREGORIAN;
// /*$bulan     = $_POST['bulan'];
// $tahun     = $_POST['tahun'];*/
// $bulan     = 2;
// $tahun     = 2024;
// $hari      = cal_days_in_month($kal, $bulan, $tahun);
// $hari1     = $hari + 3; 
// //echo "Pada Bulan ini Terdapat".$hari1."hari";
// //aray bulan
// $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
// $tahunKabisat = array("2020", "2024");
// if ($bulan == 2 && $tahun == $tahunKabisat[1]) {
// 	echo "Bulan Kabisat <br>";
// 	echo "Bulan ".$BulanIndo[(int)$bulan-1]." ".$hari." Hari, Tahun ".$tahun;
// }else{
// 	echo "Bulan ".$BulanIndo[(int)$bulan-1]." ".$hari." Hari, Tahun ".$tahun;
// }
// 

// $nama = NULL;

// $nama1  = isset($nama) ? "'".$nama."'" : NULL;

// var_dump($nama1);
// echo $nama1;
if (empty($_POST)) {
	
}
else{
$day = $_POST['day'];
$tgl = $_POST['tgl'];
$newDate = date("Y-m-d", strtotime($tgl));
$tgl2= date('d-m-Y', strtotime('+'.$day.' day', strtotime( $newDate )));}


// $tgl1 = "2013-01-23";// pendefinisian tanggal awal
// $tgl2 = date('Y-m-d', strtotime('+6 days', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari
// echo $tgl2; //print tanggal

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Cek Limit Tanggal Jatuh Tempo</title>
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
	<link href="assets/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" />
	<link href="assets/datepicker/css/datepicker.css" rel="stylesheet" />
	<style>
		    .isi{
		    	display: block;
		    	padding-bottom: 21px;
		    	text-align:  center;
		    	/* margin: 0 0 10px; */
		    	font-family:  cursive;
		    	font-size: 17px;
		    	font-weight:  bold;
		    	/* line-height: 20px; */
		    	/* word-break: break-all; */
		    	/* word-wrap: break-word; */
		    	white-space: pre;
		    	/* white-space: pre-wrap; */
		    	background-color: #f5f5f5;
		    	border: 1px solid #ccc;
		    	border: 1px solid rgba(0,0,0,0.15);
		    	-webkit-border-radius: 4px;
		    	-moz-border-radius: 4px;
		    	border-radius: 4px;
		    }
	</style>
</head>
<body>
	
	<form action="tes2.php" method="POST" style="padding:50px;">

		<div>
			<input class="datepicker " id="tgl" name="tgl" type="text" placeholder="Tanggal Faktur" required="true" autocomplete="off" />
			<input class="span6 " id="day" name="day" type="text" placeholder="Lama Jatuh Tempo" maxlength="3" onkeyup="validAngka(this)" required="true" autocomplete="off" />
			<button type="submit" class="btn btn-success" id="cariLaporanMskBtn"><i class="fa fa-search"></i> Hitung</button>
		</div>
		
	</form>

	<div class="isi">
		<?php 

		if (empty($_POST)) {
			
		}
		else
		{
			echo " Tanggal Faktur ".$tgl.",  Lama Jatuh Tempo ".$day." Hari";
			echo ", Tanggal Jatuh Tempo ".$tgl2;
		}

			

		?>
	</div>

	<footer style="text-align: center; color: #0273b5;">
		&copy;Ian Septian
	</footer>

	<script src="assets/js/jquery-1.8.3.min.js"></script>
	<script src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="assets/datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$('.datepicker').datepicker({
			    format: 'dd-mm-yyyy',
			});

		});
		function validAngka(a)
		{
		  if(!/^[0-9.]+$/.test(a.value))
		  {
		  a.value = a.value.substring(0,a.value.length-1000);
		  }
		}

		/*function HurufBesar(a){
		  setTimeout(function(){
		      a.value = a.value.toUpperCase();
		  }, 1);
		}*/

		//pesan error ajax
		$(document).ajaxError(function(){
			alert("Terjadi Kesalahan, Lakukan Refresh Halaman. Lihat error_log");
		});
	</script>

</body>
</html>