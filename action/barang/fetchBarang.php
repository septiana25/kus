<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../class/saldo.php';

$saldoClass = new Saldo($koneksi);

try {
	$getMondthAndYear = $saldoClass->getSaldoByLastDate();
	$month = date('m', strtotime($getMondthAndYear));
	$year = date('Y', strtotime($getMondthAndYear));
	$result = handleFetchAllSaldo($saldoClass, $month, $year);

	echo json_encode($result);
} catch (Exception $e) {
	echo $e->getMessage();
} finally {
	$koneksi->close();
}

function generateButton($id_brg, $id)
{
	return '<div class="btn-group">
		<button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
		<ul class="dropdown-menu">
			<li><a href="detailsaldo.php?id=' . $id . '"><i class="fa fa-eye" aria-hidden="true"></i> Detail</a></li>
			<li><a href="#editModalBarang" onclick="editBarang(' . $id_brg . ')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
			<li><a href="#hapusModalBarang" onclick="hapusBarang(' . $id_brg . ')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
		</ul>
 	</div>';
}

function handleFetchAllSaldo($saldoClass, $month, $year)
{
	$result = $saldoClass->getAllSaldo($month, $year);
	$output = array('data' => array());

	while ($row = $result->fetch_array()) {
		if ($row['jumlah'] > 0 || $row['jumlah'] == '-') {
			$button = generateButton($row['id_brg'], $row['id']);
			$output['data'][] = array(
				$row['nourt'],
				$row['kdbrg'],
				$row['brg'],
				$row['rak'],
				$row['kat'],
				$row['saldo_awal'],
				$row['tahunprod'],
				$row['jumlah'],
				$row['saldo_akhir'],
				$button
			);
		}
	}

	return $output;
}
