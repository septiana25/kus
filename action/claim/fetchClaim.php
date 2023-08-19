<?php

require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';

	$tahun = date("Y");
	$bulan = date("m");

	$query = "SELECT id_claim, pengaduan, tgl, toko, sales, brg, kerusakan, keputusan, nominal FROM claim 
		JOIN barang USING(id_brg)
		WHERE nota = 'N' ORDER BY id_claim DESC";
	$result = $koneksi->query($query);

	$output = array('data' => array());

	if ($result->num_rows > 0 ) {

		while ($row = $result->fetch_array()) {
			$id_claim = $row['id_claim'];
			$tgl = TanggalIndo($row['tgl']);
			$nominal = format_rupiah($row['nominal']);

			if ($row[7] == 'Proses') 
			{
				$keputusan = '<span class="label label-warning">Proses..</span>';
				$print = '';
			}else
			{
				$keputusan = $row[7];
				$print = '<li><a href="dataClaim.php?p=print&id='.$id_claim.'" data-toggle="modal"><i class="fa fa-print"></i> Print</a></li>';
			}

			$button = '
			  <div class="btn-group">
		         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
		         <ul class="dropdown-menu">
		             <li><a href="#modalEdit" onclick="editClaim('.$id_claim.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
		             <li><a href="#hapusModalClaim" onclick="hapusClaim('.$id_claim.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>';
		    $button .= $print;
		    $button .= '
		         </ul>
		      </div>';

			$output['data'][] = array(
				$row[1],
				$tgl,
				$row[3],
				$row[4],
				$row[5],
				$row[6],
				$keputusan,
				$nominal,
				$button);
		}
	}
	$koneksi->close();

	echo json_encode($output);

?>