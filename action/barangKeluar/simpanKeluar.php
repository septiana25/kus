<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';
require_once '../class/keluar.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) { //jika data data post

	$detailSaldoClass = new DetailSaldo($koneksi);
	$keluarClass = new Keluar($koneksi);

	$id_barang  = $koneksi->real_escape_string($_POST["id_brg"]);
	$id_rak     = $koneksi->real_escape_string($_POST["id_rak"]);
	$jml        = $koneksi->real_escape_string($_POST["jml"]);
	//$nama       = $koneksi->real_escape_string($_POST["pengirim"]);
	$noFaktur   = $koneksi->real_escape_string($_POST["noFaktur"]);
	$id_toko    = $koneksi->real_escape_string($_POST["id_toko"]);
	$keterangan = $koneksi->real_escape_string($_POST["keterangan"]);
	$awal       = $koneksi->real_escape_string($_POST["awal"]);
	$tgl        = $koneksi->real_escape_string($_POST["tgl"]);
	$faktur     = $awal . $noFaktur;
	$namaLogin  = $_SESSION['nama'];

	//$tgl            = date("Y-m-d");
	$jam         = date("H:i:s");
	$tgl1 		 = date("Y-m-d H:i:s");
	$bulan       = SUBSTR($tgl, 5, -3);
	$tahun       = SUBSTR($tgl, 0, -6);

	//query cek tanggal saldo
	$cekTglSaldo    = $koneksi->query("SELECT MONTH(tgl) FROM saldo ORDER BY id_saldo DESC LIMIT 0,1");
	$rowCekTglSldo  = $cekTglSaldo->fetch_array();
	$bulanSaldo     = $rowCekTglSldo[0];

	$sql_success = "";

	//membuat fungsi transaksi
	$koneksi->begin_transaction();

	if ($bulanSaldo == $bulan) {

		//query cek no faktur
		$cekNoFaktur    = $koneksi->query("SELECT id_klr FROM keluar WHERE no_faktur='$faktur'");

		//query barang
		$brg            = $koneksi->query("SELECT brg FROM barang WHERE id_brg='$id_barang'");
		$rowBrg         = $brg->fetch_array();
		$barang         = $rowBrg['brg'];

		//query detail_brg
		$id_detailSaldo = $id_rak;
		$detail_brg     = $koneksi->query("SELECT id, jumlah FROM detail_saldo WHERE id_detailsaldo = '$id_detailSaldo'");
		$rowDetail_brg  = $detail_brg->fetch_array();
		$id             = $rowDetail_brg['id'];
		$jumlah         = $rowDetail_brg['jumlah'];

		//query saldo
		$saldo          = $koneksi->query("SELECT id_saldo, saldo_awal, saldo_akhir FROM saldo WHERE id='$id' AND MONTH(tgl)='$bulan' AND YEAR(tgl)='$tahun'");
		$rowSaldo       = $saldo->fetch_array();
		$id_saldo       = $rowSaldo['id_saldo'];
		$cek_saldo      = $rowSaldo['saldo_akhir'];

		$ket            = "Keluar " . $barang;

		if ($cek_saldo >= $jml && $jumlah >= $jml) //cek saldo akhir & check jumlah detail saldo
		{

			//query input keluar
			$keluar         = $koneksi->query("SELECT id_klr, id_toko FROM keluar WHERE tgl='$tgl' AND no_faktur='$faktur' AND id_toko = '$id_toko'");
			$rowKeluar      = $keluar->fetch_array();
			$id_klr         = $rowKeluar['id_klr'];
			$toko     		= $rowKeluar['id_toko'];

			if ($keluar->num_rows == 1) //jika data keluar ada
			{

				if ($id_toko == $toko) {



					$query_det_klr = "INSERT INTO detail_keluar (id_klr, id, jam, jml_klr, sisaRtr, ket)
												  		 VALUES  ('$id_klr', '$id', '$jam', '$jml', '$jml', '$keterangan')";
					$insert_DetailKeluar = $koneksi->query($query_det_klr);
					$id_DetailKeluar = $koneksi->insert_id;
					$detailKeluarTahunProd =  handleSaveKeluarTahunProd($keluarClass, $detailSaldoClass, $id_detailSaldo, $id_DetailKeluar);

					if ($insert_DetailKeluar === TRUE && $detailKeluarTahunProd['success']) //cek jika data table detail keluar berhasil disimpan
					{

						if ($saldo->num_rows == 1) //cek jika data saldo ada satu
						{

							$sub_saldo     = $rowSaldo['saldo_akhir']; //get saldo akhir
							$total_saldo   = $sub_saldo - $jml; //saldo akhir dikurangi jumlah

							$update_saldo  = "UPDATE saldo SET saldo_akhir ='$total_saldo', tgl = '$tgl' 
														 WHERE id_saldo    ='$id_saldo'";

							$updateDetailSaldo = handleDetailSaldo($detailSaldoClass, $id_detailSaldo, $jml);

							if ($koneksi->query($update_saldo) === TRUE && $updateDetailSaldo['success']) {

								$valid['success']  = true;
								$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan ";

								$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action)
																			VALUES('$namaLogin', '$tgl1', '$ket', 't')");

								$sql_success .= "success";
							} else //cek jika update saldo berhasil
							{

								$valid['success']  = false;
								$valid['messages'] = "<strong>Error! </strong>Data Saldo Gagal Disimpan. Di Tabel Saldo, Keluar Ada Error-AIG-0D01 " . $koneksi->error; //pesan gagal
							}
						} else //cek jika data saldo duplikat
						{
							$valid['success']  = false;
							$valid['messages'] = "<strong>Error! </strong>Data Saldo Duplikat/Tidak Ada. Error-AIG-0D02 " . $koneksi->error; //pesan gagal

						}
					} else //cek jika data table detail keluar gagal di simpan
					{

						$valid['success']  = false;
						$valid['messages'] = "<strong>Error! </strong>Data Gagal Disimpan. Di Tabel Detail Keluar Error-AIG-0D03 " . $koneksi->error; //pesan gagal

					}
				} else {

					$valid['success']  = false;
					$valid['messages'] = "<strong>Warning! </strong>Nama Toko Tidak Sama Dengan No Faktur Sebelumnya. Error-AIG-0D13 ";
				}
			} //end jika data keluar ada


			/*-------------------------------------------------------------------*/ else if ($keluar->num_rows == 0) //jika data keluar tidak ada
			{

				if ($cekNoFaktur->num_rows == 0) //cek jika no faktur kosong
				{

					$insert_keluar = "INSERT INTO keluar ( no_faktur, id_toko, tgl, pembuat)
												  VALUES ( '$faktur', '$id_toko' , '$tgl', '$namaLogin')";

					if ($koneksi->query($insert_keluar) === TRUE) //cek jika data table keluar berhasil di simpan
					{

						$id_klr = $koneksi->insert_id;

						if ($saldo->num_rows == 1) //cek jika saldo ada satu 
						{


							$query_det_klr = "INSERT INTO detail_keluar (id_klr, id, jam, jml_klr, sisaRtr, ket)
														  	VALUES  ('$id_klr', '$id', '$jam', '$jml', '$jml', '$keterangan')";

							$insert_DetailKeluar = $koneksi->query($query_det_klr);
							$id_DetailKeluar = $koneksi->insert_id;
							$detailKeluarTahunProd =  handleSaveKeluarTahunProd($keluarClass, $detailSaldoClass, $id_detailSaldo, $id_DetailKeluar);

							if ($insert_DetailKeluar === TRUE && $detailKeluarTahunProd['success']) //cek jika data table detail keluar berhasil disimpan
							{

								$sub_saldo     = $rowSaldo['saldo_akhir']; //get saldo akhir
								$total_saldo   = $sub_saldo - $jml; //saldo akhir di kurangi jumlah keluar

								$update_saldo  = "UPDATE saldo SET saldo_akhir ='$total_saldo', tgl = '$tgl' 
															    WHERE id_saldo ='$id_saldo'";

								$updateDetailSaldo = handleDetailSaldo($detailSaldoClass, $id_detailSaldo, $jml);

								if ($koneksi->query($update_saldo) === TRUE && $updateDetailSaldo['success']) {

									$valid['success']  = true;
									$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan ";

									$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action)
																			VALUES('$namaLogin', '$tgl1', '$ket', 't')");

									$sql_success .= "success";
								} else //cek jika update saldo berhasil
								{

									$valid['success']  = false;
									$valid['messages'] = "<strong>Error! </strong>Data Saldo Gagal Disimpan. Di Tabel Saldo, Keluar Ada Error-AIG-0D01 " . $koneksi->error; //pesan gagal
								}
							} else //cek jika data table detail keluar gagal disimpan
							{

								$valid['success']  = false;
								$valid['messages'] = "<strong>Error! </strong>Data Gagal Disimpan. Di Tabel Detail Keluar, Keluar Tidak Ada Error-AIG-0D05 " . $koneksi->error; //pesan gagal

							}
						} else //cek jika saldo duplikat atau tidak ada
						{

							$valid['success']  = false;
							$valid['messages'] = "<strong>Warning! </strong> Data Saldo Tidak Ada/Duplikat. Error-AIG-0D06 Id Detail Barang " . $id;
						}
					} //end cek jika data table keluar berhasil di simpan

					else //cek jika data table keluar gagal di simpan
					{

						$valid['success']  = false;
						$valid['messages'] = "<strong>Error! </strong>Data Gagal Disimpan. Di Tabel Keluar Error-AIG-0D07 " . $koneksi->error; //pesan gagal


					}
				} //end cek jika no faktur kosong 
				else {
					$valid['success']  = false;
					$valid['messages'] = "<strong>Warning! </strong>No Faktur Sudah Ada Error-AIG-0002"; //pesan gagal
				}
			} //end jika data keluar tidak ada

			else //cek jika data duplikat
			{

				$valid['success']  = false;
				$valid['messages'] = "<strong>Warning! </strong>Data Duplikat. Di Tabel Keluar Error-AIG-0D08 " . $id_klr;
			} //end cek jika data duplikat

		} //end cek saldo akhir


		/*------------------------------------------------------------------------*/ else //cek jika saldo lebih kecil dari saldo 
		{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Warning! </strong>Jumlah Terlalu Besar Error-AIG-0006";
		} //end cek jika saldo lebih kecil dari saldo
	} else {

		$valid['success']  = false;
		$valid['messages'] = "<strong>Warning! </strong> Hanya Boleh Input Di Bulan Sekarang Error-AIG-0005";
	}

	/*====================< Fungsi Rollback dan Commit >========================*/
	if ($sql_success) {

		$koneksi->commit(); //simpan semua data simpan

	} else {

		$koneksi->rollback(); //batal semua data simpan

	}
	/*====================< Fungsi Rollback dan Commit >========================*/

	$koneksi->close();

	echo json_encode($valid);
} //end jika data data post

function handleDetailSaldo($detailSaldoClass, $idDetailSaldo, $jml)
{
	global $valid;

	try {
		$checkDetailSaldo = $detailSaldoClass->getDetailSaldoByidDetailsaldo($idDetailSaldo);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diambil. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	$resultDetailSaldo = $checkDetailSaldo->fetch_array();
	$idDetailSaldo = $resultDetailSaldo['id_detailsaldo'];
	$totalJumlah = $resultDetailSaldo['jumlah'] - $jml;
	$updateDetailSaldo = $detailSaldoClass->update($idDetailSaldo, $totalJumlah);

	if (!$updateDetailSaldo['success']) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo ";
		return $valid;
	}

	return $updateDetailSaldo;
}

function handleSaveKeluarTahunProd($keluarClass, $detailSaldoClass, $idDetailSaldo, $id_DetailKeluar)
{
	global $valid;

	try {
		$checkDetailSaldo = $detailSaldoClass->getDetailSaldoByidDetailsaldo($idDetailSaldo);
	} catch (Exception $e) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Diambil. Di Tabel Saldo. Error: " . $e->getMessage();
		return $valid;
	}

	$resultDetailSaldo = $checkDetailSaldo->fetch_array();
	$tahunprod = $resultDetailSaldo['tahunprod'];
	$saveKeluarTahunProd = $keluarClass->saveTahunProd($id_DetailKeluar, $tahunprod);

	if (!$saveKeluarTahunProd['success']) {
		$valid['success'] = false;
		$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo ";
		return $valid;
	}

	return $saveKeluarTahunProd;
}
