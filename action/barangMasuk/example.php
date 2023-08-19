<?php
	include_once("../../function/helper.php");
	include_once("../../function/koneksi.php");
	include_once("../../function/setjam.php");
	require_once '../../function/session.php';

	$id_barang = $_POST["id_barang"];
	$id_rak    = $_POST["id_rak"];
	$jml       = $_POST["jml"];
	/*$tgl       = date("Y-m-d");
	$jam 	   = date("H:i:s");
	$bulan = date("m");
	$tahun = date("Y");*/
	$tgl       = "2017-04-16";
	$jam 	   = date("H:i:s");
	$bulan = "04";
	$tahun = "2017";

/*-> untuk update brg_msk berdasarkan tgl, id_brg dan id_rak pakai fungsi coun
  -> untuk menghitung saldo awal pakai fungsi count jika 0 insert dari jml
	 jika data ada ambil dari yang terakhir pake fungsi DESC dan limit 0,1
	 SELECT * FROM brg_msk WHERE id_brg='1' AND id_rak='1' ORDER BY tgl_msk DESC LIMIT 0,1
  -> untuk update saldo awal pakai kolom di baca 1 jika 2 tidak di update*/

	//query detail_brg
	$detail_brg    = mysqli_query($koneksi, "SELECT * FROM detail_brg WHERE id_brg='$id_barang' AND id_rak='$id_rak'");
	$detail_barang = mysqli_fetch_assoc($detail_brg);
	$id            = $detail_barang['id'];
	
	//query input dan update brg_msk
	$masuk         = mysqli_query($koneksi, "SELECT * FROM masuk WHERE id='$id' AND tgl='$tgl'");
	$brg_msk       = mysqli_fetch_assoc($masuk);
	$id_msk        = $brg_msk['id_msk'];
	
	//query detail_saldo
	$saldo         = mysqli_query($koneksi, "SELECT * FROM saldo WHERE id='$id' AND MONTH(tgl)='$bulan' AND YEAR(tgl)='$tahun'");
	$saldo2        = mysqli_fetch_assoc($saldo);
	$id_saldo      = $saldo2['id_saldo'];
	
	//query saldo cek saldo akhir
	$saldo_akhir   = mysqli_query($koneksi, "SELECT * FROM saldo WHERE id='$id' ORDER BY tgl DESC LIMIT 0,1");
	$akhir         = mysqli_fetch_assoc($saldo_akhir);

	//action table detail_brg
	if (mysqli_num_rows($detail_brg)==1) {//data barang ada
		$stok       = $detail_barang['stok'];
		$total_stok = $stok + $jml;
		$update_brg = mysqli_query($koneksi, "UPDATE detail_brg SET stok='$total_stok' WHERE id='$id'");
		if ($update_brg) {//cek apakah detail_brg usdah masuk database

			if (mysqli_num_rows($masuk)==1) {//data masuk ada
				$sub_msk    = $brg_msk['total_msk'];
				$total_msk  = $sub_msk + $jml;
				$update_msk = mysqli_query($koneksi, "UPDATE masuk SET total_msk='$total_msk' WHERE id_msk='$id_msk'");
				if ($update_msk) {
					$insert_det_msk = mysqli_query($koneksi, "INSERT INTO detail_masuk (id_msk,
																						jam,
																						jml_msk)
																		  VALUES 		('$id_msk',
																		  				'$jam',
																		  				'$jml')");
					if ($insert_det_msk) {//jika detail masuk berhasil disimpan
						if (mysqli_num_rows($saldo)==1) {//jika data saldo ada
							$sub_saldo    = $saldo2['saldo_akhir'];
							$total_saldo  = $sub_saldo + $jml;
							echo "sub saldo : $sub_saldo total_saldo : $total_saldo<br>";
							$update_saldo = mysqli_query($koneksi, "UPDATE saldo SET saldo_akhir='$total_saldo' 
																				 WHERE id_saldo='$id_saldo'");
							if ($update_saldo) {
								echo "Sukses ";
							} else {
								echo "Gagal";
							}
							
						}//end jika data saldo ada
						else {//jika data saldo tidak ada
							$saldo_awal = $akhir['saldo_akhir'];
							$total_akhir = $saldo_awal + $jml;
							$insert_saldo = mysqli_query($koneksi, "INSERT INTO saldo (id,
																					   tgl,
																					   saldo_awal,
																					   saldo_akhir)
																				VALUES
																						('$id',
																						 '$tgl',
																						 '$saldo_awal',
																						 '$total_akhir')");
							if ($insert_saldo) {
								echo "Sukses //data masuk ada";
							} else {
								echo "Gagal";
							}
						}//end jika data saldo tidak ada
					}//end jika detail masuk berhasil disimpan
				}//end data masuk ada
			}//end jika data saldo ada
			else{//data masuk tidak ada
				$insert_msk = mysqli_query($koneksi, "INSERT INTO masuk (id,
																		 tgl,
																		 total_msk)
																  VALUES('$id',
																		 '$tgl',
																		 '$jml')");

				if ($insert_msk) {//jika masuk berhasil di simpan
					$id_masuk       = mysqli_insert_id($koneksi);
					$insert_det_msk = mysqli_query($koneksi, "INSERT INTO detail_masuk (id_msk,
																						jam,
																						jml_msk)
																		  VALUES 		('$id_masuk',
																		  				'$jam',
																		  				'$jml')");					
					
					if ($insert_det_msk) {//jika detail masuk berhasil disimpan
						if (mysqli_num_rows($saldo)==1) {//jika data saldo ada
							$sub_saldo    = $saldo2['saldo_akhir'];
							$total_saldo  = $sub_saldo + $jml;
							$update_saldo = mysqli_query($koneksi, "UPDATE saldo SET saldo_akhir='$total_saldo' 
																				 WHERE id_saldo='$id_saldo'");
							if ($update_saldo) {
								echo "Sukses ";
							} else {
								echo "Gagal".mysqli_error($koneksi);
							}
							
						}//end jika data saldo ada
						else {//jika data saldo tidak ada
							$saldo_awal = $akhir['saldo_akhir'];
							$total_akhir = $saldo_awal + $jml;
							$insert_saldo = mysqli_query($koneksi, "INSERT INTO saldo (id,
																					   tgl,
																					   saldo_awal,
																					   saldo_akhir)
																				VALUES
																						('$id',
																						 '$tgl',
																						 '$saldo_awal',
																						 '$total_akhir')");
							if ($insert_saldo) {
								echo "Sukses //data masuk tidak ada";
							} else {
								echo "Gagal".mysqli_error($koneksi);
							}
						}//end jika data saldo tidak ada
					}//end jika detail masuk berhasil disimpan
				}//end jika masuk berhasil di simpan
			}//end data masuk tidak ada
		}//end cek apakah detail_brg usdah masuk database
	}//end data barang ada

	elseif (mysqli_num_rows($detail_brg)>=2) {//jika data di table lebih dari 1
		echo "Data Duplikat dgn id: $id";
	}//end jika data di table lebih dari 1

	else{//data barang tidak ada
		$insert_brg = mysqli_query($koneksi, "INSERT INTO detail_brg (id_brg,
																	  id_rak,
																	  stok)
															VALUES	  ('$id_barang', 
																	   '$id_rak',
																	   '$jml')");
		if ($insert_brg) {//jika detail_brg berhasil di simpan
			$id         = mysqli_insert_id($koneksi);
			$insert_msk = mysqli_query($koneksi, "INSERT INTO masuk (id,
																		 tgl,
																		 total_msk)
																VALUES	('$id',
																		 '$tgl',
																		 '$jml')");
			if ($insert_msk) { //jika detail_masuk berhasil di simpan
				$id_masuk       = mysqli_insert_id($koneksi);
				$insert_det_msk = mysqli_query($koneksi, "INSERT INTO detail_masuk (id_msk,
																					jam,
																					jml_msk)
																	  VALUES 		('$id_masuk',
																	  				'$jam',
																	  				'$jml')");
				$insert_saldo	= mysqli_query($koneksi, "INSERT INTO saldo (id,
																			 tgl,
																			 saldo_akhir)
																	   VALUES
																			 ('$id',
																			  '$tgl',
																			  '$jml')"); 
				if ($insert_det_msk && $insert_saldo) {
					echo "Sukses";
				} else {
					echo "Gagal";
				}
			}//end jika detail_brg berhasil di simpan
		} //end jika detail_brg berhasil di simpan
	}//end data barang tidak ada

		/*if (mysqli_num_rows($count)==1) {
			$det_msk   = mysqli_query($koneksi, "INSERT INTO detail_msk (id_msk,
																	  jam,
																	  jml_msk)
															VALUES   ('$id_msk',
																	 '$jam',
																	 '$jml')");

			if ($det_msk) {
				//barang masuk
				$subtotal     = $row['total_msk'];
				$total        = $subtotal + $jml;
				//detail saldo
				$subakhir     = $saldo2['saldo_akhir'];
				$totalakhir   = $subakhir + $jml;
				
				//barang masuk
				$update       = mysqli_query($koneksi, "UPDATE brg_msk SET total_msk='$total' WHERE id_msk='$id_msk'");
				//detail saldo
				$update_saldo = mysqli_query($koneksi, "UPDATE detail_saldo SET saldo_akhir='$totalakhir' WHERE id_saldo='$saldo2[id_saldo]'");
					if ($update) {
						echo "Sukses";
					}else{
						echo "gagal";
					}
			}

		}elseif(mysqli_num_rows($count)==0){
			$brg_msk   = mysqli_query($koneksi, "INSERT INTO brg_msk (id_brg,
																	  id_rak,
																	  tgl_msk)
															 VALUES  ('$id_barang',
															 		  '$id_rak',
															 		  '$tgl')");
			if ($brg_msk) {
				$id_msk  = mysqli_insert_id($koneksi);
				$det_msk = mysqli_query($koneksi, "INSERT INTO detail_msk (id_msk,
																		  jam,
																		  jml_msk)
																VALUES   ('$id_msk',
																		 '$jam',
																		 '$jml')");

				if ($det_msk) {
					$saldo_awal        = $saldo2['saldo_akhir'];
					$total_saldo_akhir = $saldo_awal + $jml;
					$saldo_akhir       = mysqli_query($koneksi, "INSERT INTO detail_saldo (id_brg,
																				     id_rak,
																				     tgl,
																				     saldo_awal,
																				     saldo_akhir)
																		VALUES		 ('$id_barang',
																					  '$id_rak',
																					  '$tgl',
																					  '$saldo_awal',
																					  '$total_saldo_akhir')");

					$update = mysqli_query($koneksi, "UPDATE brg_msk SET total_msk='$jml' WHERE id_msk='$id_msk'");
					if ($update AND $saldo_akhir) {
						echo "Sukses";
					}else{
						echo "gagal";
					}
				}
			}
		}else{
			echo "Error data double";
		}*/
			
?>