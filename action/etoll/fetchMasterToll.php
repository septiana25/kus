<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../../function/fungsi_rupiah.php';

$query = "SELECT a.id_toll, no_toll, pemegang, no_pol, saldoTambah-saldoKurang AS total
			FROM(
			SELECT id_toll, no_toll, pemegang, no_pol, saldo, SUM(IFNULL(bayar, 0)) AS saldoKurang FROM tblDetTransToll
			JOIN tblTransToll USING(id_trans)
			RIGHT JOIN tblEToll USING(id_toll)
			GROUP BY no_toll
			) a
			LEFT JOIN(
			SELECT id_toll, SUM(IFNULL(tmbh_saldo, 0)) AS saldoTambah FROM tblTmbhSaldo
			RIGHT JOIN tblEToll USING(id_toll)
			GROUP BY no_toll
			)b ON a.id_toll=b.id_toll ORDER BY no_toll ASC";
$result = $koneksi->query($query);
$output = array('data' => array());

if ($result->num_rows > 0) {
	$no = 1;
	while ($row = $result->fetch_assoc()) {
	$id_toll = $row['id_toll'];
	$button = '<div class="btn-group">
         <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
         <ul class="dropdown-menu">
             <li><a href="#editNoTollModal" id="editNoToll1" onclick="editNoToll('.$id_toll.')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
             <li><a href="#hapusNoTollModal" onclick="hapusNoToll('.$id_toll.')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
         </ul>
      </div>';

     $output['data'][] = array(
     	$no,
     	$row['no_toll'],
     	$row['pemegang'],
     	$row['no_pol'],
     	format_rupiah($row['total']),
     	$button);
     $no++;
	}
}

$koneksi->close();

echo json_encode($output);
?>