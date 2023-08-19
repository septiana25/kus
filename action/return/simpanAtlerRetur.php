<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

	$alterFaktur = $koneksi->real_escape_string($_POST['alterFaktur']);
	$alterFaktur1= $koneksi->real_escape_string($_POST['alterFaktur1']);
	$alterRetur  = $koneksi->real_escape_string($_POST['alterRetur']);
	$alterTglRtr = $koneksi->real_escape_string($_POST['alterTglRtr']);
	$alterBrg    = $koneksi->real_escape_string($_POST['alterBrg']);
	$alterId_rak = $koneksi->real_escape_string($_POST['alterId_rak']);
	$alterJmlRtr = $koneksi->real_escape_string($_POST['alterJmlRtr']);
	$alterKet    = $koneksi->real_escape_string($_POST['alterKet']);
	$id_toko     = $koneksi->real_escape_string($_POST['id_toko']);
	$noRetrur    = "R".date("y").".00000".$fakturRetur;
	$noFaktur    = $alterFaktur."-".$alterFaktur1;
	$namaLogin   = $_SESSION['nama'];
	
	//$tgl       = date("Y-m-d");
	$jam         = date("H:i:s");
	$tgl1        = date("Y-m-d H:i:s");
	$bulan       = SUBSTR($alterTglRtr, 5,-3);
	$tahun       = SUBSTR($alterTglRtr, 0,-6);

	$cekTglSaldo    = $koneksi->query("SELECT MONTH(tgl) FROM saldo ORDER BY id_saldo DESC LIMIT 0,1");
	$rowCekTglSldo  = $cekTglSaldo->fetch_array();
	$bulanSaldo     = $rowCekTglSldo[0]; 

	$sql_success = "";

	//membuat fungsi transaksi
	$koneksi->begin_transaction();


	if ($bulanSaldo == $bulan)
	{

		//$cekNoRetrur    = $koneksi->query("SELECT id_msk FROM masuk WHERE suratJln='$noRetrur'");

			//query input dan update brg_msk
			$masuk    = $koneksi->query("SELECT id_msk FROM masuk WHERE tgl='$alterTglRtr' AND suratJln = '$noRetrur'");
			$rowMasuk = $masuk->fetch_array();
			$id_msk   = $rowMasuk['id_msk'];

			//query detail_brg
			$detail_brg    = $koneksi->query("SELECT id FROM detail_brg WHERE id_brg = '$alterBrg' AND id_rak= $alterId_rak");
			$rowDetail_brg = $detail_brg->fetch_array();
			$id            = $rowDetail_brg['id'];

			//query cek no surat jalan
			$cekNoRetrur    = $koneksi->query("SELECT id_msk FROM masuk WHERE suratJln='$noRetrur' AND MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun ORDER BY id_msk DESC LIMIT 0,1");

			$saldo    = $koneksi->query("SELECT id_saldo, saldo_awal, saldo_akhir FROM saldo WHERE id ='$id' 
										 AND MONTH(tgl)= '$bulan' AND YEAR(tgl)='$tahun'");
			$rowSaldo = $saldo->fetch_array();
			$id_saldo = $rowSaldo['id_saldo'];

			//query Barang
			$brg           = $koneksi->query("SELECT brg FROM barang WHERE id_brg = '$alterBrg'");
			$rowBrg        = $brg->fetch_array();
			$barang        = $rowBrg['brg'];

			$ket           = "Masuk ".$barang;


			# -------------------------< action table masuk >---------------------------------
			
			if ($masuk->num_rows == 1)//cek jika masuk ada satu
			{

				if ($detail_brg->num_rows == 1)//cek jika detail barang ada satu
				{
					
					$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
												  		VALUES  ('$id_msk', '$id', '$jam', '$alterJmlRtr', '$alterKet  ')";

					if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
					{

						if ($saldo->num_rows == 1)//cek jika saldo ada satu
						{

							$sub_saldo   = $rowSaldo['saldo_akhir'];//get saldo akhir
							$total_saldo = $sub_saldo + $alterJmlRtr;//saldo akhir tambah jumlah masuk

							$update_saldo = "UPDATE saldo SET saldo_akhir = $total_saldo
														  WHERE id_saldo  = $id_saldo";

							if ($koneksi->query($update_saldo) === TRUE) //cek jika data tabel saldo berhasil disimpan
							{

								$valid['success']  = true;
								$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
								
								$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) 
															  			VALUES('$namaLogin', '$tgl1', '$ket', 't')");
								$sql_success .="success";

							}
							else //cek jika data tabel saldo gagal disimpan
							{

								$valid['success']  = false;
								$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0C02 ".$koneksi->error;	
									
							}

						}
						else//cek jika saldo tidak ada atau ganda
						{

							$valid['success']  = false;
							$valid['messages'] = "<strong>Error! </strong> Data Saldo Tidak Ada/Duplikat. Error-AIG-0C03 Id Detail Barang ".$id;

						}
						
					}
					else //cek jika data tabel detail masuk gagal disimpan
					{

						$valid['success']  = false;
						$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di tabel detail masuk Error-AIG-0C04 ".$koneksi->error;

					}				

				}
				elseif ($detail_brg->num_rows == 0)//cek jika detail barang tidak ada
				{

					$insert_brg = "INSERT INTO detail_brg (id_brg, id_rak)
												   VALUES ('$alterBrg', '$alterId_rak')";

					if ($koneksi->query($insert_brg) === TRUE) //cek jika data tabel masuk berhasil disimpan
					{
						
						$id             = $koneksi->insert_id; //get id masuk 

						$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
													  		VALUES  ('$id_msk', '$id', '$jam', '$alterJmlRtr', '$alterKet')";

						if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
						{
							
							if ($saldo->num_rows == 0)//cek jika saldo kosong
							{

								$insert_saldo = "INSERT INTO saldo (id, tgl, saldo_awal, saldo_akhir)
															    VALUES ('$id', '$alterTglRtr', '0', '$alterJmlRtr')";

								if ($koneksi->query($insert_saldo) === TRUE) //cek jika data tabel saldo berhasil disimpan
								{

									$valid['success']  = true;
									$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
									
									$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) 
																  			VALUES('$namaLogin', '$tgl1', '$ket', 't')");
									$sql_success .="success";

								}
								else //cek jika data tabel saldo gagal disimpan
								{

									$valid['success']  = false;
									$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0C06 ".$koneksi->error;									

								}

							}
							else//cek jika saldo ada
							{

								$valid['success']  = false;
								$valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat. Error-AIG-0C07 Id Detail Barang ".$id;

							}

						}
						else //cek jika data tabel detail masuk gagal disimpan
						{

							$valid['success']  = false;
							$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Masuk Error-AIG-0C08 ".$koneksi->error;

						}

					}
					else //cek jika data tabel masuk gagal disimpan
					{

						$valid['success']  = false;
						$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di tabel detail barang Error-AIG-0C09 ".$koneksi->error;
						
					}

				}
				else//cek jika detail barang duplikat
				{

					$valid['success']  = false;
					$valid['messages'] = "<strong>Warning! </strong> Data Duplikat Di Table Detail Barang. Error-AIG-0C10 Id Detail Barang ".$id;

				}

			}
			elseif ($masuk->num_rows == 0)//cek jika masuk tidak ada
			{

				if ($cekNoRetrur->num_rows == 0)// cek jika surat jalan tidak ada
				{

					if ($detail_brg->num_rows == 1)//cek jika detail barang ada satu
					{
					
						$insert_msk = "INSERT INTO masuk (tgl, suratJln, no_faktur, toko1, retur)
												   VALUES('$alterTglRtr', '$noRetrur','$noFaktur', '$id_toko', '1')";

						if ($koneksi->query($insert_msk) === TRUE) //cek jika data tabel masuk berhasil disimpan
						{

							$id_msk = $koneksi->insert_id;

							$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
														  		VALUES  ('$id_msk', '$id', '$jam', '$alterJmlRtr', 
														  				 '$alterKet')";

							if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
							{

								if ($saldo->num_rows == 1)//cek jika saldo kosong
								{

									$sub_saldo   = $rowSaldo['saldo_akhir'];//get saldo akhir
									$total_saldo = $sub_saldo + $alterJmlRtr;//saldo akhir tambah jumlah masuk

									$update_saldo = "UPDATE saldo SET saldo_akhir = $total_saldo
																  WHERE id_saldo  = $id_saldo";

									if ($koneksi->query($update_saldo) === TRUE) //cek jika data tabel saldo berhasil disimpan
									{

										$valid['success']  = true;
										$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
										
										$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) 
																	  			VALUES('$namaLogin', '$tgl1', '$ket', 't')");
										$sql_success .="success";

									}
									else //cek jika data tabel saldo gagal disimpan
									{

										$valid['success']  = false;
										$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0C12 ".$koneksi->error;									

									}
								}
								else//cek jika saldo ada
								{

									$valid['success']  = false;
									$valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat/Tidak Ada. Error-AIG-0C13 Id Detail Barang ".$id;

								}

							}
							else //cek jika data tabel detail masuk gagal disimpan
							{

								$valid['success']  = false;
								$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Masuk Error-AIG-0C14 ".$koneksi->error;							
							
							}

						}
						else //cek jika data tabel masuk gagal disimpan
						{

							$valid['success']  = false;
							$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Masuk Error-AIG-0C15 ".$koneksi->error;
							
						}

					}
					elseif ($detail_brg->num_rows == 0)//cek jika detail barang tidak ada
					{
						
						$insert_brg = "INSERT INTO detail_brg (id_brg, id_rak)
													   VALUES ('$alterBrg', '$alterId_rak')";

						if ($koneksi->query($insert_brg) === TRUE) //cek jika data tabel detail barang berhasil disimpan
						{ 
							
							$id             = $koneksi->insert_id; //get id masuk 

							$insert_msk = "INSERT INTO masuk (tgl, suratJln, no_faktur, toko1, retur)
													   VALUES('$alterTglRtr', '$noRetrur','$noFaktur', '$id_toko', '1')";

							if ($koneksi->query($insert_msk) === TRUE) //cek jika data tabel masuk berhasil disimpan
							{

								$id_msk = $koneksi->insert_id;

								$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
															  		VALUES  ('$id_msk', '$id', '$jam', '$alterJmlRtr', 
															  				 '$alterKet  ')";

								if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
								{

									if ($saldo->num_rows == 0)//cek jika saldo kosong
									{

										$insert_saldo = "INSERT INTO saldo (id, tgl, saldo_awal, saldo_akhir)
																	    VALUES ('$id', '$alterTglRtr', '0', '$alterJmlRtr')";

										if ($koneksi->query($insert_saldo) === TRUE) //cek jika data tabel saldo berhasil disimpan
										{

											$valid['success']  = true;
											$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
											
											$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) 
																		  			VALUES('$namaLogin', '$tgl1', '$ket', 't')");
											$sql_success .="success";

										}
										else //cek jika data tabel saldo gagal disimpan
										{

											$valid['success']  = false;
											$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0C17 ".$koneksi->error;									

										}
									}
									else//cek jika saldo ada
									{

										$valid['success']  = false;
										$valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat. Error-AIG-0C18 Id Detail Barang ".$id;

									}

								}
								else //cek jika data tabel detail masuk gagal disimpan
								{

									$valid['success']  = false;
									$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Masuk Error-AIG-0C19 ".$koneksi->error;							
								
								}

							}
							else //cek jika data tabel masuk gagal disimpan
							{

								$valid['success']  = false;
								$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Masuk Error-AIG-0C20 ".$koneksi->error;
								
							}

						}
						else //cek jika data tabel detail barang gagal disimpan
						{

							$valid['success']  = false;
							$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di tabel detail barang Error-AIG-0C21 ".$koneksi->error;
							
						}
					}
					else//cek jika detail barang duplikat
					{

						$valid['success']  = false;
						$valid['messages'] = "<strong>Warning! </strong> Data Duplikat Di Table Detail Barang. Error-AIG-0C22 Id Detail Barang ".$id;

					}

				}
				else//cek jika surat jalan ada
				{

					$valid['success']  = false;
					$valid['messages'] = "<strong>Warning! </strong> No Retur Sudah Ada/No Retur Tidak Sama Dengan No Faktur Sebelumnya Error-AIG-0002 ";

				}

			}
			else//cek jika masuk duplikat
			{

				$valid['success']  = false;
				$valid['messages'] = "<strong>Warning! </strong> Data Duplikat Di Table Masuk. Error-AIG-0C23 Id Masuk ".$id_msk;

			}

			# -------------------------< action table masuk >---------------------------------			

		//}

	}
	else
	{

		$valid['success']  = false;
		$valid['messages'] = "<strong>Warning! </strong> Hanya Boleh Input Di Bulan Sekarang Error-AIG-0005";

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
