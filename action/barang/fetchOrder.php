<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/setjam.php';
	require_once '../../function/session.php';

	$tahun          = date("Y");
	$bulan          = date("m");
	$id = $_GET['id'];
	$sql = "SELECT id_order, nama_brg, qty, CONCAT('Rp.', IFNULL(harga, '-'), ket_Det) AS ket FROM detail_order WHERE id_order = $id";


	$result = $koneksi->query($sql);

	$output = array('data' => array());

	if ($result->num_rows > 0) {

	while ($row = $result->fetch_array()) {
	$id_brg = $row[0];

	$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">
             <li><a href="#editModalBarang" onclick="editBarang('.$id_brg.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
             <li><a href="#hapusModalBarang" onclick="hapusBarang('.$id_brg.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
         </ul>
      </div>';
	   


	$output['data'][] = array(
		$row['nama_brg'],
		$row['qty'],
		$row['ket'],
		$button);
	}//while
	}//if
$koneksi->close();

echo json_encode($output);
?>