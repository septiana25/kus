<?php
function TanggalIndo($date){
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 	//2017-04-21
	$tahun = substr($date, 0, 4);
	$bulan = substr($date, 5, 2);
	$tgl   = substr($date, 8, 2);
 
	//$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
	$result = $tgl . "-" . $bulan . "-". $tahun;		
	return($result);
}

function TanggalHuruf($date){
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
 	//2017-04-21
	$tahun = substr($date, 0, 4);
	$bulan = substr($date, 5, 2);
	$tgl   = substr($date, 8, 2);
 
	$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
	//$result = $tgl . "-" . $bulan . "-". $tahun;		
	return($result);
}
?>