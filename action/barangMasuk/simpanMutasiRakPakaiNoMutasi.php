<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

	$asalRakMTSRak = $koneksi->real_escape_string($_POST["asalRakMTSRak"]);
	$NoMTSRak      = $koneksi->real_escape_string($_POST["NoMTSRak"]);
	$NoMTSRakAkhr  = $koneksi->real_escape_string($_POST["NoMTSRakAkhr"]);
	$tglMTSRak     = $koneksi->real_escape_string($_POST["tglMTSRak"]);
	$ketMTSRak     = $koneksi->real_escape_string($_POST["ketMTSRak"]);
	$id_brgMTSRak  = $koneksi->real_escape_string($_POST["id_brgMTSRak"]);
	$id_rakMTSRak  = $koneksi->real_escape_string($_POST["id_rakMTSRak"]);
	$jmlMTSRak     = $koneksi->real_escape_string($_POST["jmlMTSRak"]);
	$NoMutasi      = $NoMTSRak.$NoMTSRakAkhr;
	$namaLogin     = $_SESSION['nama'];
	
	//$tgl         = date("Y-m-d");
	$jam           = date("H:i:s");
	$tgl1          = date("Y-m-d H:i:s");
	$bulan         = SUBSTR($tglMTSRak, 5,-3);
	$tahun         = SUBSTR($tglMTSRak, 0,-6);
	
	//query Barang
	$cekTglSaldo   = $koneksi->query("SELECT MONTH(tgl) FROM saldo ORDER BY id_saldo DESC LIMIT 0,1");
	$rowCekTglSldo = $cekTglSaldo->fetch_array();
	$bulanSaldo    = $rowCekTglSldo[0];
	
	//query cek saldo mutasi rak
	$cekId         = $koneksi->query("SELECT id FROM detail_brg WHERE id_brg = '$id_brgMTSRak'
					 				  AND id_rak= '$asalRakMTSRak'");
	$rowId 		   = $cekId->fetch_array();
	$idMTS         = $rowId['id'];
	
	$cekMTSRak     = $koneksi->query("SELECT id_saldo, saldo_akhir FROM saldo WHERE id = '$idMTS' AND MONTH(tgl)= '$bulan'
									  AND YEAR(tgl)='$tahun'");
	$rowCekMTSRak  = $cekMTSRak->fetch_assoc();
	$saldoAsal 	   = $rowCekMTSRak['saldo_akhir'];
	$id_saldoAsal  = $rowCekMTSRak['id_saldo'];

	if ($cekMTSRak->num_rows == 0)
	{
		$sisasaldo = 'Barang Tidak Ada Di Rak Asal';
	}
	else
	{
		$sisasaldo = 'Jumlah Terlalu Besar. Sisa ' .$saldoAsal;
	}


	$sql_success = "";

	//membuat fungsi transaksi
	$koneksi->begin_transaction();

	if ($bulanSaldo == $bulan)
	{

		if ($saldoAsal >= $jmlMTSRak)//cek jika saldo lebih besar dari jumlah
		{
			
			//query input dan update brg_msk
			$masuk    = $koneksi->query("SELECT id_msk FROM masuk  WHERE tgl='$tglMTSRak' 
										 AND suratJln = '$NoMutasi'");
			$rowMasuk = $masuk->fetch_array();
			$id_msk   = $rowMasuk['id_msk'];

			//query detail_brg
			$detail_brg    = $koneksi->query("SELECT id FROM detail_brg WHERE id_brg = $id_brgMTSRak 
											  AND id_rak= $id_rakMTSRak");
			$rowDetail_brg = $detail_brg->fetch_array();
			$id            = $rowDetail_brg['id'];

			$ceksuratJLN   = $koneksi->query("SELECT id_msk FROM masuk WHERE suratJln='$NoMutasi'");

			$saldo    = $koneksi->query("SELECT id_saldo, saldo_awal, saldo_akhir FROM saldo WHERE id ='$id' 
										 AND MONTH(tgl)= '$bulan' AND YEAR(tgl)='$tahun'");
			$rowSaldo = $saldo->fetch_array();
			$id_saldo = $rowSaldo['id_saldo'];

			//query Barang
			$brg           = $koneksi->query("SELECT brg FROM barang WHERE id_brg = '$id_brgMTSRak'");
			$rowBrg        = $brg->fetch_array();
			$barang        = $rowBrg['brg'];

			$ket           = "Masuk ".$barang;


			# -------------------------< action table masuk >---------------------------------
			
			if ($masuk->num_rows == 1)//cek jika masuk ada satu
			{


				if ($detail_brg->num_rows == 1)//cek jika detail barang ada satu
				{
					
					$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
												  		VALUES  ('$id_msk', '$id', '$jam', '$jmlMTSRak', '$ketMTSRak')";

					if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
					{

						if ($saldo->num_rows == 1)//cek jika saldo ada satu
						{

							$sub_saldo   = $rowSaldo['saldo_akhir'];//get saldo akhir
							$total_saldo = $sub_saldo + $jmlMTSRak;//saldo akhir tambah jumlah masuk

							$update_saldo = "UPDATE saldo SET saldo_akhir = $total_saldo
														  WHERE id_saldo  = $id_saldo";

							if ($koneksi->query($update_saldo) === TRUE) //cek jika data tabel saldo berhasil disimpan
							{

								$saldoSisa =  $saldoAsal-$jmlMTSRak;

								$updateSaldoSisa = "UPDATE saldo SET saldo_akhir = $saldoSisa 
													WHERE id_saldo = $id_saldoAsal";

								if ($koneksi->query($updateSaldoSisa) === TRUE)
								{

									$valid['success']  = true;
									$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

									$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$namaLogin', '$tgl1', '$ket', 't')");

									$sql_success .="success";

								}
								else
								{

									$valid['success']  = false;
									$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-00100 ".$koneksi->error;

								}

							}
							else //cek jika data tabel saldo gagal disimpan
							{

								$valid['success']  = false;
								$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0001 ".$koneksi->error;	
									
							}

						}
						else//cek jika saldo tidak ada atau ganda
						{

							$valid['success']  = false;
							$valid['messages'] = "<strong>Error! </strong> Data Saldo Tidak Ada/Duplikat. Error-AIG-0002 Id Detail Barang ".$id;

						}
						
					}
					else //cek jika data tabel detail masuk gagal disimpan
					{

						$valid['success']  = false;
						$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di tabel detail masuk Error-AIG-0003 ".$koneksi->error;

					}				

				}
				elseif ($detail_brg->num_rows == 0)//cek jika detail barang tidak ada
				{

					$insert_brg = "INSERT INTO detail_brg (id_brg, id_rak)
												   VALUES ('$id_brgMTSRak', '$id_rakMTSRak')";

					if ($koneksi->query($insert_brg) === TRUE)//cek jika data tabel masuk berhasil disimpan
					{

						$id             = $koneksi->insert_id; //get id detail barang 

						$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
											  		VALUES  ('$id_msk', '$id', '$jam', '$jmlMTSRak', '$ketMTSRak')";

						if ($koneksi->query($insert_det_msk) === TRUE)
						{
							
							if ($saldo->num_rows == 0)//cek jika saldo kosong
							{
								
								$insert_saldo = "INSERT INTO saldo (id, tgl, saldo_awal, saldo_akhir)
															    VALUES ('$id', '$tglMTSRak', '0', '$jmlMTSRak')";

								if ($koneksi->query($insert_saldo) === TRUE) //cek jika data tabel saldo berhasil disimpan
								{
									
									$saldoSisa =  $saldoAsal-$jmlMTSRak;

									$updateSaldoSisa = "UPDATE saldo SET saldo_akhir = $saldoSisa 
														WHERE id_saldo = $id_saldoAsal";

									if ($koneksi->query($updateSaldoSisa) === TRUE)// cek jika saldo sisa berhasil di simpan
									{
										
										$valid['success']  = true;
										$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

										$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$namaLogin', '$tgl1', '$ket', 't')");

										$sql_success .="success";

									}
									else //cek jika data tabel saldo gagal disimpan
									{

										$valid['success']  = false;
										$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0010 ".$koneksi->error;									

									}

								}
								else//cek jika data tabel saldo sisa gagal disimpan
								{

									$valid['success']  = false;
									$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0010 ".$koneksi->error;

								}

							}
							else//cek jika saldo ada
							{

								$valid['success']  = false;
								$valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat. Error-AIG-0011 Id Detail Barang ".$id;

							}

						}
						else //cek jika data tabel detail masuk gagal disimpan
						{

							$valid['success']  = false;
							$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Masuk Error-AIG-0006 ".$koneksi->error;

						}

					}
					else //cek jika data tabel detail barang gagal disimpan
					{

						$valid['success']  = false;
						$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di tabel detail barang Error-AIG-0007 ".$koneksi->error;
						
					}

				}
				else//cek jika detail barang duplikat
				{

					$valid['success']  = false;
					$valid['messages'] = "<strong>Warning! </strong> Data Duplikat Di Table Detail Barang $id Error-AIG-0016";

				}

			}
			elseif ($masuk->num_rows == 0)//cek jika masuk tidak ada
			{

				if ($ceksuratJLN->num_rows == 0)// cek jika surat jalan tidak ada
				{

					if ($detail_brg->num_rows == 1)//cek jika detail barang ada satu
					{
					
						$insert_msk = "INSERT INTO masuk (tgl, suratJln, id_toko, id_rak, retur)
												   VALUES('$tglMTSRak', '$NoMutasi', '$tokoMTSRak', '$asalRakMTSRak','3')";

						if ($koneksi->query($insert_msk) === TRUE) //cek jika data tabel masuk berhasil disimpan
						{

							$id_msk = $koneksi->insert_id;

							$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
												  		VALUES  ('$id_msk', '$id', '$jam', '$jmlMTSRak', '$ketMTSRak')";

							if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
							{

								if ($saldo->num_rows == 1)//cek jika saldo kosong
								{

									$sub_saldo   = $rowSaldo['saldo_akhir'];//get saldo akhir
									$total_saldo = $sub_saldo + $jmlMTSRak;//saldo akhir tambah jumlah masuk

									$update_saldo = "UPDATE saldo SET saldo_akhir = $total_saldo
																  WHERE id_saldo  = $id_saldo";

									if ($koneksi->query($update_saldo) === TRUE) //cek jika data tabel saldo berhasil disimpan
									{

										$valid['success']  = true;
										$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

										$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$namaLogin', '$tgl1', '$ket', 't')");

										$sql_success .="success";

									}
									else //cek jika data tabel saldo gagal disimpan
									{

										$valid['success']  = false;
										$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0010 ".$koneksi->error;									

									}
								}
								else//cek jika saldo ada
								{

									$valid['success']  = false;
									$valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat/Tidak Ada. Error-AIG-0011 Id Detail Barang ".$id;

								}

							}
							else //cek jika data tabel detail masuk gagal disimpan
							{

								$valid['success']  = false;
								$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Masuk Error-AIG-0012 ".$koneksi->error;							
							
							}

						}
						else //cek jika data tabel masuk gagal disimpan
						{

							$valid['success']  = false;
							$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Masuk Error-AIG-0013 ".$koneksi->error;
							
						}

					}
					elseif ($detail_brg->num_rows == 0)//cek jika detail barang tidak ada
					{
						
						$insert_brg = "INSERT INTO detail_brg (id_brg, id_rak)
													   VALUES ('$id_brgMTSRak', '$id_rakMTSRak')";

						if ($koneksi->query($insert_brg) === TRUE) //cek jika data tabel detail barang berhasil disimpan
						{ 
							
							$id         = $koneksi->insert_id; //get id masuk 

							$insert_msk = "INSERT INTO masuk (tgl, suratJln, id_toko, id_rak, retur)
													   VALUES('$tglMTSRak', '$NoMutasi', '$tokoMTSRak', '$asalRakMTSRak','3')";

							if ($koneksi->query($insert_msk) === TRUE) //cek jika data tabel masuk berhasil disimpan
							{

								$id_msk = $koneksi->insert_id;

								$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
													  		VALUES  ('$id_msk', '$id', '$jam', '$jmlMTSRak', '$ketMTSRak')";

								if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
								{

									if ($saldo->num_rows == 0)//cek jika saldo kosong
									{

										$insert_saldo = "INSERT INTO saldo (id, tgl, saldo_awal, saldo_akhir)
																	    VALUES ('$id', '$tglMTSRak', '0', '$jmlMTSRak')";

										if ($koneksi->query($insert_saldo) === TRUE) //cek jika data tabel saldo berhasil disimpan
										{

											$saldoSisa =  $saldoAsal-$jmlMTSRak;

											$updateSaldoSisa = "UPDATE saldo SET saldo_akhir = $saldoSisa 
																WHERE id_saldo = $id_saldoAsal";

											if ($koneksi->query($updateSaldoSisa) === TRUE)
											{

												$valid['success']  = true;
												$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

												$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$namaLogin', '$tgl1', '$ket', 't')");

												$sql_success .="success";

											}
											else
											{

												$valid['success']  = false;
												$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-00100 ".$koneksi->error;

											}


										}
										else //cek jika data tabel saldo gagal disimpan
										{

											$valid['success']  = false;
											$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0010 ".$koneksi->error;									

										}
									}
									else//cek jika saldo ada
									{

										$valid['success']  = false;
										$valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat. Error-AIG-0011 Id Detail Barang ".$id;

									}

								}
								else //cek jika data tabel detail masuk gagal disimpan
								{

									$valid['success']  = false;
									$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Masuk Error-AIG-0012 ".$koneksi->error;							
								
								}

							}
							else //cek jika data tabel masuk gagal disimpan
							{

								$valid['success']  = false;
								$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Masuk Error-AIG-0013 ".$koneksi->error;
								
							}

						}
						else //cek jika data tabel detail barang gagal disimpan
						{

							$valid['success']  = false;
							$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di tabel detail barang Error-AIG-0007 ".$koneksi->error;
							
						}
					}
					else//cek jika detail barang duplikat
					{

						$valid['success']  = false;
						$valid['messages'] = "<strong>Warning! </strong> Data Duplikat Di Table Detail Barang $id Error-AIG-0016";

					}

				}
				else//cek jika surat jalan ada
				{

					$valid['success']  = false;
					$valid['messages'] = "<strong>Warning! </strong> No Mutasi Sudah Ada Error-AIG-0014 ";

				}

			}
			else//cek jika masuk duplikat
			{

				$valid['success']  = false;
				$valid['messages'] = "<strong>Warning! </strong> Data Duplikat Di Table Masuk $id  Error-AIG-0016";

			}

			# -------------------------< action table masuk >---------------------------------

		}
		else//cek jika saldo lebih kecil dari jumlah
		{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Warning! </strong> ".$sisasaldo;

			/*$valid['success']  = false;
			$valid['messages'] = "<strong>Warning! </strong> Jumlah Lebih Besar Dari Saldo. Sisa ".$saldoAsal;*/

		}
	}
	else
	{

		$valid['success']  = false;
		$valid['messages'] = "<strong>Warning! </strong> Hanya Boleh Input Di Bulan Sekarang Error-AIG-0017";

		
	}

/*====================< Fungsi Rollback dan Commit >========================*/
	if ($sql_success)
	{

		$koneksi->commit();//simpan semua data simpan

	}
	else
	{

		$koneksi->rollback();//batal semua data simpan

	}
/*====================< Fungsi Rollback dan Commit >========================*/

	$koneksi->close();

	echo json_encode($valid);

}
?>
