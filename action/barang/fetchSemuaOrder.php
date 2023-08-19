<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/setjam.php';
	require_once '../../function/session.php';

	$tahun          = date("Y");
	$bulan          = date("m");
	$sql = "SELECT id_order, CONCAT('PO1910-000',id_order) AS no_PO, status, toko, UPPER(sales), tglOrder FROM tblOrder WHERE status !='BARU' ORDER BY id_order DESC";


	$result = $koneksi->query($sql);

	$output = array('data' => array());

	if ($result->num_rows > 0) {

	while ($row = $result->fetch_array()) {
	$id_order = $row[0];

	$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">
             <li><a href="#" onclick="printOrder('.$id_order.')" data-toggle="modal"><i class="icon-pencil"></i> Print</a></li>
             <li><a href="#" onclick="hapusBarang('.$id_order.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
         </ul>
      </div>';

      if ($row[2] == 'BELUM POSTING') {
      	$pesan = '<span class="label label-warning">'.$row[2].'</span>';
      }else{
      $pesan = '<span class="label label-info">'.$row[2].'</span>';
	  } 


	$output['data'][] = array(
		$row[1],
		$pesan,
		$row[3],
		$row[4],
		$row[5],
		$button);
	}//while
	}//if
$koneksi->close();

echo json_encode($output);
?>