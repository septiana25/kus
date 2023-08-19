<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';
	require_once '../../function/setjam.php';

	$valid['success'] = array('success' => false, 'messages' => array());

	if ($_POST) {

		$id_klr = $_POST['editIdKlr'];
		$awalFaktur = $_POST['editAwalFaktur'];
		$akhirFaktur = $_POST['editAkhirFaktur'];
		$noFaktur = $awalFaktur.''.$akhirFaktur;
		$nama = $_SESSION['nama'];
		$tgl = date("Y-m-d H:i:s");

		$cek_noFaktur = "SELECT no_faktur FROM keluar WHERE id_klr=$id_klr";
		$result = $koneksi->query($cek_noFaktur);
		$row = $result->fetch_array();
		$fakturLama = $row['no_faktur'];
		$ket ="Efaktur ".$fakturLama." Menjadi ".$noFaktur;

		$cekFakturGanda = "SELECT no_faktur FROM keluar WHERE no_faktur='$noFaktur'";
		$res = $koneksi->query($cekFakturGanda);

		if ($res->num_rows == 1) {
			$valid['success']  = 'cek_faktur';
			$valid['messages'] = "<strong>Error! </strong>No Faktur Sudah Ada";
		}else{
			$sql = "UPDATE keluar SET no_faktur = '$noFaktur' WHERE id_klr=$id_klr";
			if ($koneksi->query($sql) === TRUE) {
				$insertLog = $koneksi->query("INSERT INTO log (nama, tgl, ket, action) VALUES('$nama', '$tgl', '$ket', 'e')");
				$valid['success']  = true;
				$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
			}else{
				$valid['success']  = false;
				$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan ".$koneksi->error;
			}
		}

		$koneksi->close();

		echo json_encode($valid);

	}
?>