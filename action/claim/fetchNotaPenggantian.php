<?php
	require_once '../../function/koneksi.php';
	require_once '../../function/session.php';
	require_once '../../function/fungsi_rupiah.php';

	$query = "SELECT idNota, toko, tblNota.tglNota, COUNT(id_claim) AS item, keputusan, total
			FROM tblNota
			JOIN tblDetNota USING(idNota)
			LEFT JOIN claim USING(id_claim) WHERE keputusan != 'Tolak' GROUP BY idNota";

	$result = $koneksi->query($query);

	$output = array('data' => array());

	if ($result->num_rows > 0) {

		$no =1;
		while ($row = $result->fetch_array()) {
		$idNota = $row[0];
		$total = format_rupiah($row['total']);

	      if ($_SESSION['level'] == 'administrator') 
	      {
			$hapus = '<li><a href="#hapusModalBarang" onclick="hapusBarang('.$idNota.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';
	      }
	      else
	      {
		      $hapus ='';	  

	      // $button ='<a href="" role="button" class="btn btn-small btn-primary" id="printPenggantianBtnModal" onclick="printPenggantian('.$idNota.')"> <i class="fa fa-print"></i>';	      	
	      // 
	      }

	      $button = '
			  <div class="btn-group">
		         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
		         <ul class="dropdown-menu">
		             <li><a href="#modalVIewData" onclick="lihatData('.$idNota.')" data-toggle="modal"><i class="fa fa-eye"></i> Lihat</a></li>';
		  $button .= $hapus;
		  $button .= '
		             <li><a href="" id="printPenggantianBtnModal" onclick="printPenggantian('.$idNota.')"><i class="fa fa-print"></i> Print</a></li>
		         </ul>
		      </div>';


		if ($row['keputusan'] == "Ganti") {
			$keputusan = '<span class="label label-success">Ganti</span>';
		}elseif ($row['keputusan'] == "Ganti SC"){
			$keputusan = '<span class="label label-info">Ganti SC</span>';
		}else{
			$keputusan = '<span class="label label-warning">Tolak</span>';
		}
		$item = '<span class="badge badge-important">'.$row['item'].'</span>';
		$output['data'][] = array(
			$no,
			$row[1],
			$row['tglNota'],
			$item,
			$keputusan,
			$total,
			$button);
		$no++;
		}
	}
	$koneksi->close();
	echo json_encode($output);
?>