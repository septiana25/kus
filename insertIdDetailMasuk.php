<?php
require_once('function/koneksi.php');

$msk = "SELECT * FROM masuk ORDER BY id_msk ASC";
$fetchMsk = $koneksi->query($msk);
	// echo '

	// 	<table border="1">
	// 	<tr>
	// 		<th>Id Masuk</th>
	// 		<th>Id Detail Brg</th>
	// 	</tr>
		
	// ';
	$sql_error = '';
while ($rowMsk = $fetchMsk->fetch_assoc()) {
	$id = $rowMsk['id'];
	$id_msk = $rowMsk['id_msk'];

	$insert = "UPDATE detail_masuk SET id = '$id' WHERE id_msk = '$id_msk'";

	if ($koneksi->query($insert) === TRUE) {
		echo 'Success '.$id_msk.' ';
		
	}else{
		echo '<br/> Error '.$id_msk;
		$sql_error = 'Error';
	}
	// echo '

	// 	<tr>
	// 		<td>'.$rowMsk['id_msk'].'</td>
	// 		<td>'.$rowMsk['id'].'</td>
	// 	</tr>
	// ';
	
}
/*====================< Fungsi Rollback dan Commit >========================*/
	if ($sql_error)
	{

		$koneksi->rollback();//batal semua data simpan

	}
	else
	{
		$koneksi->commit();//simpan semua data simpan
		

	}
/*====================< Fungsi Rollback dan Commit >========================*/
//echo '</table>';
?>