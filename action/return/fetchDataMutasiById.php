<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/mutasi.php';

$classMutasi = new Mutasi($koneksi);

$id_mutasi = isset($_POST['id_mutasi']) ? $_POST['id_mutasi'] : 0;

if ($id_mutasi == 0) {
    echo json_encode(['error' => 'No data found.']);
    exit;
}

try {
    $result = handleFetchMutasiById($classMutasi, $id_mutasi);
    echo json_encode($result);
} catch (\Throwable $th) {
    error_log($th);
    echo json_encode(['error' => 'An error occurred while fetching data.']);
} finally {
    $koneksi->close();
}

function handleFetchMutasiById($classMutasi, $id_mutasi)
{

    $result = $classMutasi->fetchMutasiById($id_mutasi);
    $row = $result->fetch_assoc();

    return $row;
}
