<?php
	require_once '../../function/koneksi.php';
	//require_once '../../function/tgl_indo.php';
	require_once '../../function/session.php';

	$query = "SELECT * FROM toko WHERE id_toko != 1 ORDER BY toko ASC";
	$result = $koneksi->query($query);
	$output  = array('data' => array());

	if ($result->num_rows > 0) {
		$no = 1;
		while ($row = $result->fetch_array()) {
			$id_toko = $row[0];

			$button = '<div class="btn-group">
		         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
		         <ul class="dropdown-menu">
		             <li><a href="#editModalToko" onclick="editToko('.$id_toko.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
		             <li><a href="#hapusModalToko" onclick="hapusToko('.$id_toko.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
		         </ul>
		      </div>';

		    $output['data'][] = array(
		    	$no,
		    	$row[1],
		    	$row[2],
		    	$button);
		$no++;
		}//while
	}//if
	$koneksi->close();
	echo json_encode($output);
?>