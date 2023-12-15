<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

$valid['success'] =  array('success' => false, 'messages' => array());

function getValueFromColumn($koneksi, $column, $value)
{
	$stmt = $koneksi->prepare("SELECT $column FROM barang WHERE $column = ?");
	$stmt->bind_param("s", $value);
	$stmt->execute();
	return $stmt->get_result();
}

function insertBarang($koneksi, $id_kat, $kdbrg, $nourt, $barang, $nama)
{
	$stmt = $koneksi->prepare("INSERT INTO barang (id_kat, kdbrg, nourt, brg, pembuat) VALUES (?, ?, ?, ?, ?)");
	$stmt->bind_param("sssss", $id_kat, $kdbrg, $nourt, $barang, $nama);
	return $stmt->execute();
}

function insertLog($koneksi, $nama, $tgl, $ket, $action)
{
	$stmt = $koneksi->prepare("INSERT INTO log (nama, tgl, ket, action) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("ssss", $nama, $tgl, $ket, $action);
	return $stmt->execute();
}

try {
	$id_kat     = $koneksi->real_escape_string($_POST["id_kat"]);
	$kdbrg      = $koneksi->real_escape_string($_POST["KDbarang"]);
	$barang     = $koneksi->real_escape_string($_POST["barang"]);
	$nourt      = $koneksi->real_escape_string($_POST["NOurut"]);

	$nama = $_SESSION['nama'];
	$tgl = date("Y-m-d H:i:s");
	$ket = "Baru " . $barang;

	$CheckBarang = getValueFromColumn($koneksi, 'brg', $barang);

	if ($CheckBarang->num_rows == 1) {
		$valid['success']  = 'cek_brg';
		$valid['messages'] = "<strong>Error! </strong> Nama Barang Sudah Ada. Tabel Barang Error-AIG-0002 ";
	} else if ($CheckBarang->num_rows == 0) {
		$CheckNourt = getValueFromColumn($koneksi, 'nourt', $nourt);

		if ($CheckNourt->num_rows == 0 || $CheckNourt->num_rows > 0) {
			$CheckKodeBarang = getValueFromColumn($koneksi, 'kdbrg', $kdbrg);

			if ($CheckKodeBarang->num_rows == 0) {
				$query = insertBarang($koneksi, $id_kat, $kdbrg, $nourt, $barang, $nama);

				if ($query === TRUE) {

					$valid['success']  = true;
					$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";

					$insertLog = insertLog($koneksi, $nama, $tgl, $ket, 't');
				} else {
					$valid['success']  = false;
					$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan. Tabel Barang Error-AIG-0001 " . $koneksi->error;
				}
			} else {

				$valid['success']  = 'cek_brg';
				$valid['messages'] = "<strong>Error! </strong> Kode Barang Sudah Ada. Tabel Barang Error-AIG-0002 ";
			}
		}
	} else {
		$valid['success']  = false;
		$valid['messages'] = "<strong>Error! </strong> Data  Dulikat Hubungi Staf IT. Tabel Barang Error-AIG-0003";
	}
} catch (\Throwable $th) {
	$valid['success'] = false;
	$valid['messages'] = "<strong>Error! </strong> Terjadi Kesalahan Hubungi Staf IT.";
} finally {
	$koneksi->close();

	echo json_encode($valid);
}
