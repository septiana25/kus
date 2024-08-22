<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../class/barcodebarang.php';

$classBarcode = new BarocdeBarang($koneksi);


try {
	$result = $classBarcode->fetchAll();
	$output = array('data' => array());

	while ($row = $result->fetch_array()) {
		$button = generateButton($row['id_brg'], $row['barcode_brg']);

		$output['data'][] = array(
			$row['barcode_brg'],
			$row['brg'],
			$row['satuan'],
			$row['qty'],
			$button
		);
	}
	echo json_encode($output);
} catch (Exception $e) {
	echo json_encode(array('error' => $e->getMessage()));
} finally {
	$koneksi->close();
}

function generateButton($id_brg, $barcode)
{
	return '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="#editModalBarcodebrg" onclick="editBarcodebrg(' . $id_brg . ')" data-toggle="modal"><i class="icon-pencil"></i> Edit</a></li>
            <li><a href="#hapusModalBarcodebrg" onclick="hapusBarcodebrg(' . $id_brg . ')" data-toggle="modal"><i class="icon-trash"></i> Hapus</a></li>
            <li><a href="http://192.168.1.7/generator-qrcode/index.php?value=' . $barcode . '" target="_blank" rel="noreferrer noopener"><i class="fa fa-qrcode"></i> Barcode</a></li>
        </ul>
    </div>';
}
