<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$valid['success'] =  array('success' => false, 'messages' => array());

if ($_POST) {

	$id_kat     = $koneksi->real_escape_string($_POST["id_kat"]);
	$kdbrg      = $koneksi->real_escape_string($_POST["KDbarang"]);
	$barang     = $koneksi->real_escape_string($_POST["barang"]);
	$nourt      = $koneksi->real_escape_string($_POST["NOurut"]);
	//$barang      = 'GTX12';
	$nama = $_SESSION['nama'];
	$tgl = date("Y-m-d H:i:s");
	$ket = "Baru " . $barang;

	$cek_brg = $koneksi->query("SELECT brg FROM barang WHERE brg='$barang'");

	if ($cek_brg->num_rows == 1) {
		$valid['success']  = 'cek_brg';
		$valid['messages'] = "<strong>Error! </strong> Nama Barang Sudah Ada. Tabel Barang Error-AIG-0002 ";
	} else if ($cek_brg->num_rows == 0) {

		$cek_nourt = $koneksi->query("SELECT nourt FROM barang WHERE nourt='$nourt'");

		if ($cek_nourt->num_rows == 0 or $cek_nourt->num_rows > 0) {

			$cek_kdbrg = $koneksi->query("SELECT kdbrg FROM barang WHERE kdbrg='$kdbrg'");

			if ($cek_kdbrg->num_rows == 0) {

				$query = "INSERT INTO barang (id_kat, kdbrg, nourt,
											  brg, pembuat)
									   VALUES('$id_kat', '$kdbrg', '$nourt',
									   		  '$barang', '$nama')";

				if ($koneksi->query($query) === TRUE) {

					$valid['success']  = true;
					$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

					$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 't')");
				} else {
					$valid['success']  = false;
					$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Tabel Barang Error-AIG-0001 " . $koneksi->error;
				}
			} else {

				$valid['success']  = 'cek_brg';
				$valid['messages'] = "<strong>Error! </strong> Kode Barang Sudah Ada. Tabel Barang Error-AIG-0002 ";
			}
		} else {

			$valid['success']  = 'cek_brg';
			$valid['messages'] = "<strong>Error! </strong> Nomor Urut Sudah Ada. Tabel Barang Error-AIG-0002 ";
		}
	} else {
		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong> Data  Dulikat Hubungi Staf IT. Tabel Barang Error-AIG-0003";
	}
	$koneksi->close();

	echo json_encode($valid);
}
