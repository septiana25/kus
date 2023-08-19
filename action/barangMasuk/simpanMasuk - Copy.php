<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';

	$valid['success'] = array('success' => false, 'messages' => array());

	if ($_POST) {

		$id_barang = $koneksi->real_escape_string($_POST["id_brg"]);
		$id_rak    = $koneksi->real_escape_string($_POST["id_rak"]);
		$jml       = $koneksi->real_escape_string($_POST["jml"]);	

		// $tgl       = date("Y-m-d");
		// $jam 	   = date("H:i:s");
		// $bulan = date("m");
		// $tahun = date("Y");

		$tgl       = "2017-05-16";
		$jam 	   = date("H:i:s");
		$bulan 	   = "05";
		$tahun     = "2017";

		//query detail_brg
		$detail_brg     = $koneksi->query("SELECT * FROM detail_brg WHERE id_brg='$id_barang' AND id_rak='$id_rak'");
		$rowDetail_brg  = $detail_brg->fetch_array();
		$id             = $rowDetail_brg['id']; 

		//query input dan update brg_msk
		$masuk          = $koneksi->query("SELECT * FROM masuk WHERE id='$id' AND tgl='$tgl'");
		$rowMasuk       = $masuk->fetch_array();
		$id_msk         = $rowMasuk['id_msk'];

		
		$saldo          = $koneksi->query("SELECT * FROM saldo WHERE id='$id' AND MONTH(tgl)='$bulan' AND YEAR(tgl)='$tahun'");
		$rowSaldo       = $saldo->fetch_array();
		$id_saldo       = $rowSaldo['id_saldo'];

		
		$saldo_akhir    = $koneksi->query("SELECT * FROM saldo WHERE id='$id' ORDER BY tgl DESC LIMIT 0,1");
		$rowSaldo_akhir = $saldo_akhir->fetch_array();


		//action table detail_brg
		if ($detail_brg->num_rows == 1) {//jika data detail_brg ada
			$stok       = $rowDetail_brg['stok'];
			$total_stok = $stok + $jml;

			$update_brg = "UPDATE detail_brg SET stok='$total_stok' WHERE id='$id'";

			if ($koneksi->query($update_brg) === TRUE ) {//jika query $update_brg sukses
				if ($masuk->num_rows == 1) {//jika data masuk ada
					$sub_msk    = $rowMasuk['total_msk'];
					$total_msk  = $sub_msk + $jml;

					$update_msk = "UPDATE masuk SET total_msk='$total_msk' WHERE id_msk='$id_msk'";

					if ($koneksi->query($update_msk) === TRUE) {//jika query $update_msk sukses
						$insert_det_msk = "INSERT INTO detail_masuk (id_msk,
																	 jam,
																	 jml_msk)
													  		VALUES  ('$id_msk',
													  				 '$jam',
													  				 '$jml')";

						if ($koneksi->query($insert_det_msk) === TRUE) {//jika query $insert_det_msk sukses
							if ($saldo->num_rows == 1) {//jika data saldo ada
								$sub_saldo = $rowSaldo['saldo_akhir'];
								$total_saldo = $sub_saldo + $jml;

								$update_saldo = "UPDATE saldo SET saldo_akhir='$total_saldo' 
															  WHERE id_saldo='$id_saldo'";
								if ($koneksi->query($update_saldo) === TRUE) {
									$valid['success']  = true;
									$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
									
								}else{
									$valid['success']  = false;
		 							$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;
		 							
								}
							}//end jika data saldo ada
						}//endjika query $insert_det_msk sukses
					}//end jika query $update_msk sukses
				}//end jika data masuk ada
				else{//data masuk tidak ada
					$insert_msk = "INSERT INTO masuk (id,
													  tgl,
													  total_msk)
											   VALUES('$id',
													  '$tgl',
													  '$jml')";

					if ($koneksi->query($insert_msk) === TRUE) {//jika query $insert_msk sukses
						$id_msk         = $koneksi->insert_id;

						$insert_det_msk = "INSERT INTO detail_masuk (id_msk,
																	 jam,
																	 jml_msk)
													  		 VALUES ('$id_msk',
													  				 '$jam',
													  				 '$jml')";

						if ($koneksi->query($insert_det_msk) === TRUE) {//jika query $insert_det_msk sukses

							if ($saldo->num_rows== 1) {//jika data saldo ada
								$sub_saldo     = $rowSaldo['saldo_akhir'];
								$total_saldo   = $sub_saldo + $jml;

								$update_saldo  = "UPDATE saldo SET saldo_akhir='$total_saldo',
																   tgl = '$tgl' 
															   WHERE id_saldo ='$id_saldo'";

								if ($koneksi->query($update_saldo) === TRUE) {
									$valid['success']  = true;
									$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
									
								}
								else{
									$valid['success']  = false;
		 							$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;
								}//end jika data saldo ada
							}
							else{//jika data saldo tidak ada
								$saldo_awal = $rowSaldo_akhir['saldo_akhir'];
								$total_akhir = $saldo_awal + $jml;

								$insert_saldo = "INSERT INTO saldo (id,
																  tgl,
																  saldo_awal,
																  saldo_akhir)
														   VALUES('$id',
																  '$tgl',
																  '$saldo_awal',
																  '$total_akhir')";

								if ($koneksi->query($insert_saldo) === TRUE) {
									$valid['success']  = true;
									$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
								}else{
									$valid['success']  = false;
		 							$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;
		 							
								}
							}//end jika data saldo tidak ada
						}//end jika query $insert_det_msk sukses
					}//end jika query $insert_msk sukses
				}//end data masuk tidak ada
			}//end jika query $update_brg sukses
		}//end jika data detail_brg ada
		else{//jika data detail_brg tidak ada
			$insert_brg = "INSERT INTO detail_brg (id_brg,
												   id_rak,
												   stok)
										VALUES	  ('$id_barang', 
												   '$id_rak',
												   '$jml')";

			if ($koneksi->query($insert_brg) === TRUE) {//jika detail_brg berhasil di simpan
				$id = $koneksi->insert_id;

				$insert_msk = "INSERT INTO masuk (id,
												  tgl,
												  total_msk)
										 VALUES	 ('$id',
												  '$tgl',
												  '$jml')";
				if ($koneksi->query($insert_msk)) {//jika insert_msk berhasil di simpan
					$id_msk = $koneksi->insert_id;

					$insert_det_msk = "INSERT INTO detail_masuk (id_msk,
																 jam,
																 jml_msk)
												  		 VALUES ('$id_msk',
												  				 '$jam',
												  				 '$jml')";
						  				 
					$insert_saldo = "INSERT INTO saldo (id,
														tgl,
														saldo_akhir)
												 VALUES('$id',
														'$tgl',
														'$jml')";

					if ($koneksi->query($insert_det_msk) === TRUE && $koneksi->query($insert_saldo) === TRUE) {
						$valid['success']  = true;
						$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
					}else{
						$valid['success']  = false;
		 				$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;
					}
				}//end jika insert_msk berhasil di simpan
			}//end jika detail_brg berhasil di simpan
		}//end jika data detail_brg tidak ada

		$koneksi->close();

		echo json_encode($valid);

	}
?>
