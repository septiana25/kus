<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

$nofak = $koneksi->real_escape_string($_POST['nofak']);

$query = "SELECT id_det_klr, brg FROM keluar 
			JOIN detail_keluar USING(id_klr)
			JOIN detail_brg USING(id)
			JOIN barang USING(id_brg)
			WHERE no_faktur = '$nofak' ORDER BY brg ASC";

$result = $koneksi->query($query);
$list = "<option value='0'>Pilih Ukuran..</option>";
while ($row = $result->fetch_assoc()) {
	$list .= "<option value='" . $row['id_det_klr'] . "'>" . $row['brg'] . "</option>";
}
echo $list;
