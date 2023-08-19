<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

//membuat array konfirmasi
$valid['success'] = array('success' => false, 'messages' => array(), 'idNota' => '');
if ($_POST) {
	//get data lewat POST & diamankan
	// $tglStr    = $koneksi->real_escape_string();
	$toko      = $koneksi->real_escape_string($_POST['toko']);
	$tgl       = date('Y-m-d');
	// $akhirReg  = $koneksi->real_escape_string($_POST['noReg']);
	$keputusan = $koneksi->real_escape_string($_POST['keputusan']);
	$total     = $koneksi->real_escape_string($_POST['total']);
	// $bulan     = substr($tgl, 5,2);
	// $tahun     = substr($tgl, 2,2);
	// $noReg     = 'KTA'.$bulan.$tahun.$akhirReg;
	$totalID   = $koneksi->real_escape_string($_POST['totalID']);



	// echo $totalID."<br>";
	// echo $toko."<br>";
	// echo $awalReg.$noReg."<br>";
	// echo $keputusan."<br>";
	//insert ke tbl nota

	
	// $cekNota = 

	$insert = "INSERT INTO tblNota (tglNota,
									total)
							VALUES ('$tgl',
									'$total')";
	if ($koneksi->query($insert) === TRUE) {
		$id = $koneksi->insert_id; //get id tblnota
		//get data di tabel calim
		// $queryCek = "SELECT id_claim, pattern, dot, tahun FROM claim JOIN barang USING(id_brg) WHERE toko='$toko' AND keputusan='$keputusan' AND nota='N' LIMIT 0,10";
		// $resultCek = $koneksi->query($queryCek);

		// while ($row = $resultCek->fetch_array()) {
			// $id_claim = $row['id_claim'];
			// $noSeri = $row['pattern'].'-'.$row['dot'].'-'.$row['tahun'];//menggabungkan pattern, dot, tahun

			$inserDet    = "INSERT INTO tblDetNota (idNota, id_claim, noCM, tglCM, ket) VALUES ";
			$updateClaim = "UPDATE claim SET nota='Y' WHERE id_claim IN (";//lakukan update nota di tabel claim jika berhasil simpak ke tabel nota
			for ($i=1; $i <= $totalID ; $i++) {
				$id_claim[$i] = $koneksi->real_escape_string($_POST['id_claim'.$i]);
				$noSeri[$i]   = $koneksi->real_escape_string($_POST['noSeri'.$i]);
				$noCM[$i]     = $koneksi->real_escape_string($_POST['noCM'.$i]);
				$tglCM[$i]    = date('Y-m-d', strtotime($_POST['tglCM'.$i]));
				$ket[$i]      = $koneksi->real_escape_string($_POST['ket'.$i]);
				
				$inserDet     .= "('".$id."', '".$id_claim[$i]."','".$noCM[$i]."','".$tglCM[$i]."','".$ket[$i]."'),";
				$updateClaim  .= "".$id_claim[$i].",";
			}

			$inserDet   = rtrim($inserDet, ', ');
			$id;
			$notaStatus = false;
			if ($koneksi->query($inserDet) === TRUE) {
				$valid['idNota'] = $id;
				$notaStatus      = true;

				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan ";
				$updateClaim       = rtrim($updateClaim, ', ').')';
				$koneksi->query($updateClaim);
				// $updateClain = $koneksi->query("UPDATE claim SET nota='Y' WHERE id_claim");//lakukan update nota di tabel claim jika berhasil simpak ke tabel nota
			}else{
				$valid['success']  = false;
				$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;
				$deleteNota        = $koneksi->query("DELETE FROM tblNota WHERE idNota=$id");
			}
		// }

	}else{
		$valid['success']  = false;
		$valid['messages'] =  "Data Gagal Disimpan ".$koneksi->error;
	}

	$koneksi->close();
	echo json_encode($valid);
}
?>