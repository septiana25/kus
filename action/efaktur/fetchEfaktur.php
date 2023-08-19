<?php

require_once '../../function/koneksi.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/session.php';

$sql = "SELECT * FROM keluar WHERE pengirim='Dari Ruko 238' ORDER BY id_klr DESC";

$result = $koneksi->query($sql);

$output = array('data' => array());

if ($result->num_rows > 0) {
	while ($row = $result->fetch_array()) {
		$id_klr = $row[0];

		$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">
             <li><a href="#ModalEditEFatur" onclick="editEfaktur('.$id_klr.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
             <li><a href="#hapusModalEfaktur" onclick="hapusEfaktur('.$id_klr.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
         </ul>
      </div>';

		$output['data'][] = array(
			$row[1],
			$row[2],
			$row[3],
			$button);
	}
}
$koneksi->close();
echo json_encode($output);
?>