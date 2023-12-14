<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';

$sql = "SELECT id_rak, barcode_rak, rak
        FROM barcoderak
        LEFT JOIN rak USING(id_rak)
    ";

if ($stmt = $koneksi->prepare($sql)) {
	$stmt->execute();

	$result = $stmt->get_result();
	$output = array('data' => array());

	while ($row = $result->fetch_array()) {
		$button = generateButton($row['id_rak']);

		$output['data'][] = array(
			$row['barcode_rak'],
			$row['rak'],
			$button
		);
	}

	$stmt->close();
} else {
	// Handle error
	echo "Error: " . $koneksi->error;
}

$koneksi->close();

echo json_encode($output);

function generateButton($id_brg)
{
	return '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#editModalBarang" onclick="editBarang(' . $id_brg . ')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
            <li><a href="#hapusModalBarang" onclick="hapusBarang(' . $id_brg . ')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
        </ul>
    </div>';
}
