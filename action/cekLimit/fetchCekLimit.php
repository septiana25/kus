<?php
include_once('../../function/koneksi.php');
include_once('../../function/session.php');

	$Select = "SELECT id_brg, brg, btsLimit
		FROM tblLimit RIGHT JOIN barang USING(id_brg)";

	$resSelect = $koneksi->query($Select);

	$output = array('data' => array());

	if ($resSelect->num_rows > 0) {
		$no=1;
		while ($rowLimit = $resSelect->fetch_array()) {

			$id_brg = $rowLimit[0];
			$button = '<a href="#editModalLimit" role="button" class="btn btn-small btn-primary" id="hapusKeluarBtnModal" data-toggle="modal" onclick="editLimit('.$id_brg.')"> <i class="icon-edit"></i>';

			$output['data'][] = array(
				$no,
				$rowLimit[1],
				$rowLimit[2],
				$button);
			$no++;
		}//while
	}//if

	$koneksi->close();

	echo json_encode($output);

?>