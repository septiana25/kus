<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';

	$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

	$id_det_msk  = $koneksi->real_escape_string($_POST['id_det_msk']);

	$cek_brg     = $koneksi->query("SELECT id_msk, id, jml_msk, detMsk.rak AS MskRak, id_brg FROM detail_masuk AS detMsk
									JOIN masuk USING(id_msk) JOIN detail_brg USING(id) WHERE id_det_msk=$id_det_msk");
	$rowBrg		 = $cek_brg->fetch_array();
	$id     	 = $rowBrg['id'];
	$id_msk 	 = $rowBrg['id_msk'];
	$rak   		 = $rowBrg['MskRak'];
	$id_brg 	 = $rowBrg['id_brg'];
	$jml 		 = $rowBrg['jml_msk'];

	$cek_msk     = $koneksi->query("SELECT id, jml_msk FROM detail_masuk JOIN masuk USING(id_msk) WHERE id_msk=$id_msk");

	$cek_RakAsal = $koneksi->query("SELECT id FROM detail_brg JOIN rak USING(id_rak) WHERE id_brg = $id_brg AND rak = '$rak'");
	$rowRakAsal  = $cek_RakAsal->fetch_assoc();
	$idRakAsal   = $rowRakAsal['id'];

	$cek_saldo   = $koneksi->query("SELECT id_saldo, saldo_akhir FROM saldo WHERE id=$id ORDER BY id_saldo DESC LIMIT 0,1");
	$rowSaldo    = $cek_saldo->fetch_array();
	$id_saldo    = $rowSaldo['id_saldo'];
	$total       = $rowSaldo['saldo_akhir'];

	$saldoRakAsal = $koneksi->query("SELECT id_saldo, saldo_akhir FROM saldo WHERE id=$idRakAsal ORDER BY id_saldo
									 DESC LIMIT 0,1");
	$rowSalAsal   = $saldoRakAsal->fetch_assoc();
	$totSalRak    = $rowSalAsal['saldo_akhir'];
	$id_salRak    = $rowSalAsal['id_saldo'];


	$sql_success = "";

	//membuat fungsi transaksi
	$koneksi->begin_transaction();


	if ($cek_msk->num_rows == 1)
	{

		$insert = "DELETE FROM masuk WHERE id_msk=$id_msk";

		if($koneksi->query($insert) === TRUE)
		{

			$update = "UPDATE saldo SET saldo_akhir=$total-$jml WHERE id_saldo=$id_saldo";

			if ($koneksi->query($update) === TRUE)
			{

				$upSaldAsal = "UPDATE saldo SET saldo_akhir=$totSalRak+$jml WHERE id_saldo=$id_salRak";

				if ($koneksi->query($upSaldAsal) === TRUE)
				{

					$valid['success']  = true;
					$valid['messages'] = "<strong>Success </strong> Data Berhasil Dihapus ";

					$sql_success .="success";

				}
				else
				{

					$valid['success']  = false;
					$valid['messages'] = "<strong>Error! </strong> Data Gagal Dihapus (Saldo Gagal Update). Error-AIG-0B48 ".$koneksi->error;

				}


			}
			else
			{

				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Gagal Dihapus (Saldo Gagal Update). Error-AIG-0B49 ".$koneksi->error;
			
			}

		}
		else
		{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Masuk Gagal Dihapus. Error-AIG-0B50 ".$koneksi->error;
		
		}

	}
	else
	{

		$insert = "DELETE FROM detail_masuk WHERE id_det_msk=$id_det_msk";

		if($koneksi->query($insert) === TRUE)
		{

			$update = "UPDATE saldo SET saldo_akhir=$total-$jml WHERE id_saldo=$id_saldo";

			if ($koneksi->query($update) === TRUE)
			{

				$upSaldAsal = "UPDATE saldo SET saldo_akhir=$totSalRak+$jml WHERE id_saldo=$id_salRak";

				if ($koneksi->query($upSaldAsal) === TRUE)
				{

					$valid['success']  = true;
					$valid['messages'] = "<strong>Success </strong> Data Berhasil Dihapus ";

					$sql_success .="success";

				}
				else
				{

					$valid['success']  = false;
					$valid['messages'] = "<strong>Error! </strong> Data Gagal Dihapus (Saldo Gagal Update). Error-AIG-0B51 ".$koneksi->error;

				}

			}
			else
			{

				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Gagal Dihapus (Saldo Gagal Update). Error-AIG-0B52 ".$koneksi->error;
			
			}

		}
		else
		{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Detail Masuk Gagal Dihapus. Error-AIG-0B53 ".$koneksi->error;
		
		}

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