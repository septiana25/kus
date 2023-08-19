<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

	$valid['success'] = array('success' => false, 'messages' => array());

	if ($_POST) {
		
		$id_barang  = $_POST["id_brg"];
		$id_rak     = $_POST["id_rak"];
		$jml        = $_POST["jml"];
		$nama       = $_POST["pengirim"];
		$noFaktur   = $_POST["noFaktur"];
		$toko       = $_POST["toko"];
		$keterangan = $_POST["keterangan"];
		$awal       = $_POST["awal"];
		$faktur     = $awal.$noFaktur;
		$namaLogin  = $_SESSION['nama'];
		
		$tgl            = date("Y-m-d");
		$jam            = date("H:i:s");
		$tgl1 			= date("Y-m-d H:i:s");
		$bulan          = date("m");
		$tahun          = date("Y");
		
		//query detail_brg
		$brg            = $koneksi->query("SELECT * FROM barang WHERE id_brg='$id_barang'");
		$rowBrg         = $brg->fetch_array();
		$barang         = $rowBrg['brg'];
		
		$detail_brg     = $koneksi->query("SELECT * FROM detail_brg WHERE id_brg='$id_barang' AND id_rak='$id_rak'");
		$rowDetail_brg  = $detail_brg->fetch_array();
		$id             = $rowDetail_brg['id'];
		
		//query input keluar
		$keluar         = $koneksi->query("SELECT * FROM keluar WHERE tgl='$tgl' AND no_faktur='$faktur'");
		$rowKeluar      = $keluar->fetch_array();
		$id_klr         = $rowKeluar['id_klr'];
		
		//query saldo
		$saldo          = $koneksi->query("SELECT * FROM saldo WHERE id='$id' AND MONTH(tgl)='$bulan' AND YEAR(tgl)='$tahun'");
		$rowSaldo       = $saldo->fetch_array();
		$id_saldo       = $rowSaldo['id_saldo'];
		
		//query cek saldo terakhir
		$saldo_akhir    = $koneksi->query("SELECT * FROM saldo WHERE id='$id' ORDER BY tgl DESC LIMIT 0,1");
		$rowSaldo_akhir = $saldo_akhir->fetch_array();
		$cek_saldo      = $rowSaldo_akhir['saldo_akhir'];
		
		$ket            = "Keluar ".$barang;
		//query cek saldo terakhir
		$cek_faktur    = $koneksi->query("SELECT no_faktur FROM keluar WHERE no_faktur='$faktur'");

		if ($cek_saldo >= $jml) {//cek saldo akhir

		if ($keluar->num_rows == 1) {//jika data keluar ada

			$insert_det_klr = "INSERT INTO detail_keluar (id_klr,
														 id,
														 jam,
														 jml_klr)
										  		VALUES  ('$id_klr',
										  				 '$id',
										  				 '$jam',
										  				 '$jml')";

			if ($koneksi->query($insert_det_klr) === TRUE) {//jika insert_det_klr berhasil disimpan
				$id_det_klr = $koneksi->insert_id; //get id detail keluar
				if ($saldo->num_rows== 1) {//jika data saldo ada
					$sub_saldo     = $rowSaldo['saldo_akhir'];
					$total_saldo   = $sub_saldo - $jml;//sub saldo dikurangi jumlah
					$update_saldo  = "UPDATE saldo SET saldo_akhir ='$total_saldo',
													   tgl 		   = '$tgl' 
												 WHERE id_saldo    ='$id_saldo'";

					if ($koneksi->query($update_saldo) === TRUE) {//jika update saldo berhasil
						$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$namaLogin', '$tgl1', '$ket', 't')");
						$valid['success']  = true;
						$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan ";//pesan success
					}else{//jika update saldo gagal
						$valid['success']  = false;
						$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;//pesan gagal
						$delete = $koneksi->query("DELETE FROM detail_keluar WHERE id_det_klr='$id_det_klr'");//lakukan hapus detail keluar
					}
				}//end jika data saldo ada

				else{//jika data saldo tidak ada
					$saldo_awal = $rowSaldo_akhir['saldo_akhir'];
					$total_akhir = $saldo_awal - $jml;
					$insert_saldo = "INSERT INTO saldo (id,
													    tgl,
													    saldo_awal,
													    saldo_akhir)
											     VALUES('$id',
													    '$tgl',
													    '$saldo_awal',
													    '$total_akhir')";

					if ($koneksi->query($insert_saldo) === TRUE) {//jika update saldo berhasil
						$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$namaLogin', '$tgl1', '$ket', 't')");
						$valid['success']  = true;
						$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan ";
					}else{
						$valid['success']  = false;
						$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;
						$delete = $koneksi->query("DELETE FROM detail_keluar WHERE id_det_klr='$id_det_klr'");//lakukan hapus detail keluar
					}
				}//end jika data saldo tidak ada
			}//end jika insert_det_klr berhasil disimpan
		}//end jika data keluar ada

		else{//jika data keluar tidak ada
			if ($cek_faktur->num_rows < 1) { //cek no faktur

				//if ($pengirim == $nama) {//cek nama pengirim

				$insert_keluar = "INSERT INTO keluar (
													  no_faktur,
													  pengirim,
													  tgl,
													  ket)
											 VALUES	(
											 		 '$faktur',
											 		 '$nama',
													 '$tgl',
													 '$keterangan')";

				if ($koneksi->query($insert_keluar) == TRUE) {//jika insert_keluar berhasil disimpan
					$id_klr = $koneksi->insert_id;
					$insert_det_klr = "INSERT INTO detail_keluar (id_klr,
																 id,
																 jam,
																 jml_klr)
												  		VALUES  ('$id_klr',
												  				 '$id',
												  				 '$jam',
												  				 '$jml')";
					if ($koneksi->query($insert_det_klr) === TRUE) {//jika insert_det_klr berhasil disimpan
						$id_det_klr = $koneksi->insert_id;//get id detail keluar
						if ($saldo->num_rows == 1) {//jika data saldo ada
							$sub_saldo     = $rowSaldo['saldo_akhir'];
							$total_saldo   = $sub_saldo - $jml;
							$update_saldo  = "UPDATE saldo SET saldo_akhir ='$total_saldo',
															   tgl 		   = '$tgl' 
														 WHERE id_saldo    ='$id_saldo'";

							if ($koneksi->query($update_saldo) === TRUE) {
								$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$namaLogin', '$tgl1', '$ket', 't')");
								$valid['success']  = true;
								$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan ";
							}else{
								$valid['success']  = false;
	 							$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;

	 							$delete = $koneksi->query("DELETE FROM detail_keluar WHERE id_det_klr='$id_det_klr'");
							}

						}//end jika data saldo ada
						else{//jika data saldo tidak ada
							$saldo_awal   = $rowSaldo_akhir['saldo_akhir'];
							$total_akhir  = $saldo_awal - $jml;
							$insert_saldo = "INSERT INTO saldo (id,
															    tgl,
															    saldo_awal,
															    saldo_akhir)
													     VALUES('$id',
															    '$tgl',
															    '$saldo_awal',
															    '$total_akhir')";

							if ($koneksi->query($insert_saldo) === TRUE) {
								$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$namaLogin', '$tgl1', '$ket', 't')");
								$valid['success']  = true;
								$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan ";
							}else{
								$valid['success']  = false;
	 							$valid['messages'] = "Data Gagal Disimpan ".$koneksi->error;
	 							$delete = $koneksi->query("DELETE FROM detail_keluar WHERE id_det_klr='$id_det_klr'");
							}
						}//end jika data saldo tidak ada
					}//end jika insert_det_klr berhasil disimpan
				}//end jika insert_keluar berhasil disimpan
				}//end cek no faktur
				else{
					$valid['success']  = 'cek_faktur';
					$valid['messages'] = "<strong>Error! </strong>No Faktur Sudah Ada";
				}
			//}// end cek no faktur
		}//end jika data keluar tidak ada

		}//end cek saldo akhir
		else{
			$valid['success']  = 'cek_saldo';
			$valid['messages'] = "<strong>Error! </strong>Jumlah Terlalu Besar";
		}

		$koneksi->close();

		echo json_encode($valid);
	}

?>