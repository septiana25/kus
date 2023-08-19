<?php 

require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());

$ket       = $koneksi->real_escape_string($_POST['ketMinus']);
$tgl       = $koneksi->real_escape_string($_POST['tglMinus']);
$brg       = $koneksi->real_escape_string($_POST['id_brgMinus']);
$rak       = $koneksi->real_escape_string($_POST['id_rakMinus']);
$jml       = $koneksi->real_escape_string($_POST['jmlMinus']);

$namaLogin = $_SESSION['nama'];
$jam       = date("H:i:s");
$tgl1      = date("Y-m-d H:i:s");
$bulan     = date("m");
$tahun     = date("Y");
$tahun2    = date("y");


$cekTglSaldo   = $koneksi->query("SELECT MONTH(tgl) FROM saldo ORDER BY id_saldo DESC LIMIT 0,1");
$rowCekTglSldo = $cekTglSaldo->fetch_array();
$bulanSaldo    = $rowCekTglSldo[0];

//query cek saldo mutasi rak
$cekId         = $koneksi->query("SELECT id FROM detail_brg WHERE id_brg = '$brg'
				 				  AND id_rak= '$rak'");
$rowId 		   = $cekId->fetch_array();
$idMIN         = $rowId['id'];

$cekJmlSld     = $koneksi->query("SELECT id_saldo, saldo_akhir FROM saldo WHERE id = '$idMIN' AND MONTH(tgl)= '$bulan'
								  AND YEAR(tgl)='$tahun'");
$rowCekJmlSld  = $cekJmlSld->fetch_assoc();
$saldoAsal 	   = $rowCekJmlSld['saldo_akhir'];
$id_saldoAsal  = $rowCekJmlSld['id_saldo'];

//cek no faktur untuk no urut faktur
$cekNoFak       = "SELECT no_faktur FROM keluar JOIN detail_keluar USING(id_klr) WHERE status_klr='1' ORDER BY id_klr DESC LIMIT 0,1";
$resultCekNoFak = $koneksi->query($cekNoFak);

$rowNomor = $resultCekNoFak->fetch_assoc();
// echo $rowNomor['Nomoragt'];
$nomor    = $rowNomor['no_faktur'];
$hpsHuruf = substr($nomor, 7);
// echo $hpsHuruf;
$tambah   = $hpsHuruf+1;
// echo $tambah;

$cekNomor= strlen($tambah);
// echo $cekNomor;
if (empty($nomor)) {
	$no = '00000';
}
elseif ($cekNomor == 1){
	$no = '00000';
}
elseif ($cekNomor == 2) {
	$no = '0000';
}
elseif ($cekNomor == 3) {
	$no = '000';
}

$noMin = 'KM'.$tahun2.$bulan.'-'.$no.$tambah;

//membuat fungsi transaksi
$koneksi->begin_transaction();

$sql_success = "";

if ($cekJmlSld->num_rows == 0)
{
	$sisasaldo = 'Barang Tidak Ada Di Lokasi Rak';
}
else
{
	$sisasaldo = 'Jumlah Terlalu Besar. Error-AIG-0006 Sisa ' .$saldoAsal;
}


if ($bulanSaldo == $bulan) {
	
	if ($saldoAsal >= $jml) {
		
		$insertKlr = "INSERT INTO keluar (no_faktur, id_toko, tgl) VALUES ('$noMin', 1,'$tgl')";

		if ($koneksi->query($insertKlr) === TRUE) {

			$id_klr = $koneksi->insert_id;

			$insertDetKlr = "INSERT INTO detail_keluar (id_klr, id, jml_klr, sisaRtr, jam, ket, status_klr)
												VALUES ('$id_klr','$idMIN', '$jml', 0, '$jam', '$ket', '1')";

			if ($koneksi->query($insertDetKlr) === TRUE) {

				$saldoSisa =  $saldoAsal-$jml;

				$updateSaldoSisa = "UPDATE saldo SET saldo_akhir = $saldoSisa WHERE id_saldo = $id_saldoAsal";

				if ($koneksi->query($updateSaldoSisa) === TRUE) {

					$valid['success']  = true;
					$valid['messages'] = "<strong>Success! </strong> Data Berhasil Disimpan";

					$sql_success .="success";
				}
				else
				{

					$valid['success']  = false;
					$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Saldo Error-AIG-0024 ".$koneksi->error;

				}
				
				

			}
			else
			{
				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Detail Keluar Error-AIG-0025 ".$koneksi->error;
			}

		}
		else
		{

			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Di Tabel Keluar Error-AIG-00026 ".$koneksi->error;	

		}

	}
	else
	{

		$valid['success']  = false;
		$valid['messages'] = "<strong>Warning! </strong> ".$sisasaldo;

	}

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
?>