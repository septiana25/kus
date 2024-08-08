<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';

$valid = array('success' => false, 'messages' => '');

if ($_SESSION['level'] !== "administrator") {
    $valid['messages'] = "Akses ditolak. Anda bukan administrator.";
    echo json_encode($valid);
    exit;
}

if ($_FILES['file-csv']['error'] !== UPLOAD_ERR_OK) {
    $valid['messages'] = "Error: " . $_FILES['file-csv']['error'];
    echo json_encode($valid);
    exit;
}

$filename = pathinfo($_FILES['file-csv']['name'], PATHINFO_EXTENSION);
if (strtolower($filename) !== 'csv') {
    $valid['messages'] = "Error: File harus berformat CSV";
    echo json_encode($valid);
    exit;
}

$handle = fopen($_FILES['file-csv']['tmp_name'], "r");
$dataSalesOrder = array();
$row = 0;

while (($data = fgetcsv($handle, 0, ';')) !== FALSE) {
    $row++;
    if ($row === 1) continue; // Skip header row

    // Validate data
    if (count($data) < 7) {
        $valid['messages'] = "Error: Data tidak lengkap pada baris $row";
        echo json_encode($valid);
        fclose($handle);
        exit;
    }

    $no_faktur = trim($koneksi->real_escape_string($data[0]));
    $kode_toko = trim($koneksi->real_escape_string($data[1]));
    $kdbrg = trim($koneksi->real_escape_string($data[3]));
    $qty = trim($koneksi->real_escape_string($data[5]));
    $nopol = trim($koneksi->real_escape_string($data[6]));

    // Check for empty data
    if (empty($no_faktur) || empty($kode_toko) || empty($kdbrg) || empty($qty) || empty($nopol)) {
        $valid['messages'] = "Error: Data kosong ditemukan pada baris $row";
        echo json_encode($valid);
        fclose($handle);
        exit;
    }

    // Validate numeric fields
    if (!is_numeric($qty)) {
        $valid['messages'] = "Error: Quantity harus berupa angka pada baris $row";
        echo json_encode($valid);
        fclose($handle);
        exit;
    }

    $dataSalesOrder[] = "('" . $no_faktur . "', '" . $kode_toko . "', '" . $kdbrg . "', '" . $qty . "', '" . $qty . "', '" . $nopol . "', '" . $_SESSION['id_userKUS'] . "')";
}

fclose($handle);

if (empty($dataSalesOrder)) {
    $valid['messages'] = "Error: Tidak ada data valid untuk diupload";
    echo json_encode($valid);
    exit;
}

$koneksi->begin_transaction();

try {
    $query = "INSERT INTO tmp_salesorder (no_faktur, kode_toko, kdbrg, qty, sisa, nopol, id_user) VALUES " . implode(',', $dataSalesOrder);
    if ($koneksi->query($query)) {
        $koneksi->commit();
        $valid['success'] = true;
        $valid['messages'] = "Data berhasil diupload";
    } else {
        throw new Exception($koneksi->error);
    }
} catch (Exception $e) {
    $koneksi->rollback();
    $valid['messages'] = "Error: " . $e->getMessage();
}

echo json_encode($valid);
$koneksi->close();
