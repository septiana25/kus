<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';

$query = "SELECT a1.id_toll, no_toll, pemegang, tmbh_saldo, s_awal, bayar, (tmbh_saldo + s_awal) - bayar AS s_akhir, a1.id_post, tgl_post
		FROM(	
			SELECT id_toll, no_toll, pemegang, SUM(bayar) AS bayar, id_trans, tgl_trans, s_awal, id_post, tgl_post FROM  tblTransToll
			JOIN tblDetTransToll USING(id_trans)
			JOIN tblEToll USING(id_toll)
			JOIN tblPostingEToll AS post USING(id_trans) GROUP BY id_trans
		)a1
		LEFT JOIN (
			SELECT id_toll, id_tmbh, tmbh_saldo, id_post FROM tblTmbhSaldo
			JOIN tblPostingEToll USING(id_tmbh)
			
		)a2 ON a1.id_post=a2.id_post ORDER BY pemegang, tgl_trans ASC";

$fetch = $koneksi->query($query);

$output = array('data' => array());

if ($fetch->num_rows > 0 ) {
	$no = 1;
	while ($row=$fetch->fetch_assoc()) {
	$id_post = $row['id_post'];
	$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">
             <li><a href="#viewModalPost" id="viewPost" onclick="viewPost('.$id_post.')" data-toggle="modal"><i class="fa fa-eye"></i> View</a></li>
             <li><a href="#editModalBarang" onclick="print('.$id_post.')" data-toggle="modal"><i class="fa fa-print"></i> Print</a></li>
             <li><a href="#hapusModalBarang" onclick="hapusBarang('.$id_post.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
         </ul>
      </div>';

		$output['data'][] = array(
			$no,
			$row['no_toll'],
			$row['pemegang'],
			format_rupiah($row['tmbh_saldo']),
			format_rupiah($row['s_awal']),
			format_rupiah($row['bayar']),
			format_rupiah($row['s_akhir']),
			TanggalIndo($row['tgl_post']),
			$button);
		$no++;
	}
}

$koneksi->close();

echo json_encode($output);

?>