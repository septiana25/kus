<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/pomasuk.php';

$pomasuk = new PoMasuk($koneksi);

try {
    $result = handleFetchPoMasuk($pomasuk);
    echo json_encode($result);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}

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

function handleFetchPoMasuk($pomasuk)
{
    $result = $pomasuk->fetchPoMasuk();
    $output = array('data' => array());

    while ($row = $result->fetch_array()) {
        $button = generateButton($row['id_pomsk']);

        $output['data'][] = array(
            $row['suratJln'],
            $row['no_polisi'],
            $row['brg'],
            $row['qty'],
            $row['status'],
            $button
        );
    }

    return $output;
}
