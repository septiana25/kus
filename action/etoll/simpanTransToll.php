<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../../function/fungsi_rupiah.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
	$NoEtoll    = $koneksi->real_escape_string($_POST['NoEtoll']);
	$rute       = $koneksi->real_escape_string($_POST['rute']);
	$ruteAkhir  = $koneksi->real_escape_string($_POST['ruteAkhir']);
	$bayar      = $koneksi->real_escape_string($_POST['bayar']);
	$keterangan = $koneksi->real_escape_string($_POST['keterangan']);
	$tgl        = $_POST['tgl'];
	$jam        = date("H:i:s");

	//query cek saldo E-Toll
	$cekSaldoToll = $koneksi->query("SELECT saldoTambah-saldoKurang AS total
			FROM(
			SELECT id_toll, SUM(IFNULL(bayar, 0)) AS saldoKurang FROM tblDetTransToll
			JOIN tblTransToll USING(id_trans)
			RIGHT JOIN tblEToll USING(id_toll)
			GROUP BY no_toll
			) a
			LEFT JOIN(
			SELECT id_toll, SUM(IFNULL(tmbh_saldo, 0)) AS saldoTambah FROM tblTmbhSaldo
			RIGHT JOIN tblEToll USING(id_toll)
			GROUP BY no_toll
			)b ON a.id_toll=b.id_toll WHERE a.id_toll = $NoEtoll");
	$rowCekSaldo = $cekSaldoToll->fetch_assoc();
	$totalSaldo = $rowCekSaldo['total'];

	//query cek No E-Toll
	$cekTblTrans = $koneksi->query("SELECT id_trans, id_toll FROM tblTransToll WHERE id_toll = $NoEtoll AND stus_trans = 0");
	$rowCekTrans = $cekTblTrans->fetch_assoc();
	$id_transCek = $rowCekTrans['id_trans'];

	if ($totalSaldo < $bayar) {
		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong> Saldo E-Toll Kurang, Sisa Tinggal ".format_rupiah($totalSaldo);
	}else{

		if ($cekTblTrans->num_rows == 1) {

			//query simpan data table detTransToll
			$simpanDetTrans = "INSERT INTO tblDetTransToll (id_trans, rute, ruteAkhir, bayar, jam, ket, tgl_trans)
												   VALUES  ('$id_transCek', '$rute','$ruteAkhir', '$bayar', '$jam', '$keterangan', STR_TO_DATE('$tgl', '%d-%m-%Y'))";
			if ($koneksi->query($simpanDetTrans) === TRUE) {
				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";				
			}else{
				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan ".$koneksi->error;
			}
			//end query simpan data table detTransToll
			
		}else if ($cekTblTrans->num_rows == 0){

			//query simpan tabel tblTransToll
			$simanTrans = "INSERT INTO tblTransToll (id_toll) VALUES ('$NoEtoll')";
			if ($koneksi->query($simanTrans) === TRUE) {

				//query simpan data table detTransToll
				$id_trans = $koneksi->insert_id;
				$simpanDetTrans = "INSERT INTO tblDetTransToll (id_trans, rute, ruteAkhir, bayar, jam, ket, tgl_trans)
												   VALUES  ('$id_trans', '$rute','$ruteAkhir', '$bayar', '$jam', '$keterangan', STR_TO_DATE('$tgl', '%d-%m-%Y'))";
				if ($koneksi->query($simpanDetTrans) === TRUE) {
					$valid['success']  = true;
					$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";				
				}else{
					$valid['success']  = false;
					$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan ".$koneksi->error;
				}
				//end query simpan data table detTransToll
				
			}else{
				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan ".$koneksi->error;		
			}
			//end query simpan tabel tblTransToll

		}else{
				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Duplikat Hubungi IT";			
		}
	}

	$koneksi->close();

	echo json_encode($valid);
}

?>