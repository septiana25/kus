<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

	$tokoMTSGD   = $koneksi->real_escape_string($_POST["tokoMTSGD"]);
	$NoMTSGD     = $koneksi->real_escape_string($_POST["NoMTSGD"]);
	$NoMTSGDAkhr = $koneksi->real_escape_string($_POST["NoMTSGDAkhr"]);
	$tglMTSGD    = $koneksi->real_escape_string($_POST["tglMTSGD"]);
	$ketMTSGD    = $koneksi->real_escape_string($_POST["ketMTSGD"]);
	$id_brgMTSGD = $koneksi->real_escape_string($_POST["id_brgMTSGD"]);
	$id_rakMTSGD = $koneksi->real_escape_string($_POST["id_rakMTSGD"]);
	$jmlMTSGD    = $koneksi->real_escape_string($_POST["jmlMTSGD"]);
	$NoMutasi    = $NoMTSGD.$NoMTSGDAkhr;
	$namaLogin   = $_SESSION['nama'];
	
	//$tgl       = date("Y-m-d");
	$jam         = date("H:i:s");
	$tgl1        = date("Y-m-d H:i:s");
	$bulan       = SUBSTR($tglMTSGD, 5,-3);
	$tahun       = SUBSTR($tglMTSGD, 0,-6);
	
	//query Barang
	$cekTglSaldo    = $koneksi->query("SELECT MONTH(tgl) FROM saldo ORDER BY id_saldo DESC LIMIT 0,1");
	$rowCekTglSldo  = $cekTglSaldo->fetch_array();
	$bulanSaldo     = $rowCekTglSldo[0];

	$sql_success = "";

	//membuat fungsi transaksi
	$koneksi->begin_transaction();

	if ($bulanSaldo == $bulan)
	{

		//query input dan update brg_msk
		$masuk    = $koneksi->query("SELECT id_msk FROM masuk  WHERE tgl='$tglMTSGD' AND suratJln = '$NoMutasi'");
		$rowMasuk = $masuk->fetch_array();
		$id_msk   = $rowMasuk['id_msk'];

		//query detail_brg
		$detail_brg    = $koneksi->query("SELECT id FROM detail_brg WHERE id_brg = '$id_brgMTSGD' AND id_rak= $id_rakMTSGD");
		$rowDetail_brg = $detail_brg->fetch_array();
		$id            = $rowDetail_brg['id'];

		$ceksuratJLN   = $koneksi->query("SELECT id_msk FROM masuk WHERE suratJln='$NoMutasi'");

		$saldo    = $koneksi->query("SELECT id_saldo, saldo_awal, saldo_akhir FROM saldo WHERE id ='$id' AND MONTH(tgl)= '$bulan' AND YEAR(tgl)='$tahun'");
		$rowSaldo = $saldo->fetch_array();
		$id_saldo = $rowSaldo['id_saldo'];

		//query Barang
		$brg           = $koneksi->query("SELECT brg FROM barang WHERE id_brg = '$id_brgMTSGD'");
		$rowBrg        = $brg->fetch_array();
		$barang        = $rowBrg['brg'];

		$ket           = "Masuk ".$barang;


		# -------------------------< action table masuk >---------------------------------
		
		if ($masuk->num_rows == 1)//cek jika masuk ada satu
		{

			if ($detail_brg->num_rows == 1)//cek jika detail barang ada satu
			{
				
				$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
											  		VALUES  ('$id_msk', '$id', '$jam', '$jmlMTSGD', '$ketMTSGD')";

				if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
				{

					if ($saldo->num_rows == 1)//cek jika saldo ada satu
					{

						$sub_saldo   = $rowSaldo['saldo_akhir'];//get saldo akhir
						$total_saldo = $sub_saldo + $jmlMTSGD;//saldo akhir tambah jumlah masuk

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
											   VALUES ('$id_brgMTSGD', '$id_rakMTSGD')";

				if ($koneksi->query($insert_brg) === TRUE) //cek jika data tabel masuk berhasil disimpan
				{
					
					$id             = $koneksi->insert_id; //get id masuk 

					$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
										  		VALUES  ('$id_msk', '$id', '$jam', '$jmlMTSGD', '$ketMTSGD')";

					if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
					{
						
						if ($saldo->num_rows == 0)//cek jika saldo kosong
						{

							$insert_saldo = "INSERT INTO saldo (id, tgl, saldo_awal, saldo_akhir)
														    VALUES ('$id', '$tglMTSGD', '0', '$jmlMTSGD')";

							if ($koneksi->query($insert_saldo) === TRUE) //cek jika data tabel saldo berhasil disimpan
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
							$valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat. Error-AIG-0011 Id Detail Barang ".$id;

						}

					}
					else //cek jika data tabel detail masuk gagal disimpan
					{

						$valid['success']  = false;
						$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Masuk Error-AIG-0006 ".$koneksi->error;

					}

				}
				else //cek jika data tabel masuk gagal disimpan
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
				
					$insert_msk = "INSERT INTO masuk (tgl, suratJln, id_toko, retur)
											   VALUES('$tglMTSGD', '$NoMutasi', '$tokoMTSGD', '2')";

					if ($koneksi->query($insert_msk) === TRUE) //cek jika data tabel masuk berhasil disimpan
					{

						$id_msk = $koneksi->insert_id;

						$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
											  		VALUES  ('$id_msk', '$id', '$jam', '$jmlMTSGD', '$ketMTSGD')";

						if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
						{

							if ($saldo->num_rows == 1)//cek jika saldo kosong
							{

								$sub_saldo   = $rowSaldo['saldo_akhir'];//get saldo akhir
								$total_saldo = $sub_saldo + $jmlMTSGD;//saldo akhir tambah jumlah masuk

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
												   VALUES ('$id_brgMTSGD', '$id_rakMTSGD')";

					if ($koneksi->query($insert_brg) === TRUE) //cek jika data tabel detail barang berhasil disimpan
					{ 
						
						$id             = $koneksi->insert_id; //get id masuk 

						$insert_msk = "INSERT INTO masuk (tgl, suratJln, id_toko, retur)
												   VALUES('$tglMTSGD', '$NoMutasi', '$tokoMTSGD', '2')";

						if ($koneksi->query($insert_msk) === TRUE) //cek jika data tabel masuk berhasil disimpan
						{

							$id_msk = $koneksi->insert_id;

							$insert_det_msk = "INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket)
												  		VALUES  ('$id_msk', '$id', '$jam', '$jmlMTSGD', '$ketMTSGD')";

							if ($koneksi->query($insert_det_msk) === TRUE) //cek jika data tabel detail masuk berhasil disimpan
							{

								if ($saldo->num_rows == 0)//cek jika saldo kosong
								{

									$insert_saldo = "INSERT INTO saldo (id, tgl, saldo_awal, saldo_akhir)
																    VALUES ('$id', '$tglMTSGD', '0', '$jmlMTSGD')";

									if ($koneksi->query($insert_saldo) === TRUE) //cek jika data tabel saldo berhasil disimpan
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
				$valid['messages'] = "<strong>Warning! </strong> Surat Jalan Sudah Ada Error-AIG-0014 ";

			}

		}
		else//cek jika masuk duplikat
		{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Warning! </strong> Data Duplikat Di Table Masuk $id  Error-AIG-0016";

		}

		# -------------------------< action table masuk >---------------------------------

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
