<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';

$valid['success'] =  array('success' => false, 'messages' => array());
if ($koneksi->real_escape_string($_SESSION['level']) == "administrator") {
    if ($_FILES['file-csv']['error'] == UPLOAD_ERR_OK) {
        $filename = explode(".", $_FILES['file-csv']['name']);
        if ($filename[1] == 'csv') {
            $handle = fopen($_FILES['file-csv']['tmp_name'], "r");
            $dataKoreksiSaldo = array();
            $row = 1;
            while ($data = fgetcsv($handle, 0, ';')) { // use semicolon as the delimiter
                if ($row == 1) { // Skip the first row
                    $row++;
                    continue;
                }
                $kdbrg = trim($koneksi->real_escape_string($data[0]));
                $rak = trim($koneksi->real_escape_string($data[1]));
                $barang = trim($koneksi->real_escape_string($data[2]));
                $qty = trim($koneksi->real_escape_string($data[3]));
                $tahunprod = trim($koneksi->real_escape_string($data[4]));
                $type = trim($koneksi->real_escape_string($_POST['type']));

                $dataKoreksiSaldo[] = '
                    ("' . $kdbrg . '", "' . $rak . '", "' . $barang . '", "' . $qty . '", "' . $tahunprod . '", "' . $type . '")';
                $row++;
            }
            if (isset($dataKoreksiSaldo)) {
                $koneksi->begin_transaction();
                $query = "INSERT INTO tmp_koreksisaldo (kdbrg, rak, brg, qty, tahunprod, `type`) VALUES " . implode(',', $dataKoreksiSaldo);
                if ($koneksi->query($query) === TRUE) {
                    $valid['success'] = true;
                    $valid['messages'] = "Data berhasil diupload";

                    $koneksi->commit();
                } else {
                    $valid['success'] = false;
                    $valid['messages'] = "Error: " . $query . "<br>" . $koneksi->error;

                    $koneksi->rollback();
                }
            }
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error: File harus berformat CSV";
        }
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error: " . $_FILES['file-csv']['error'];
    }
}
$koneksi->close();
echo json_encode($valid);
