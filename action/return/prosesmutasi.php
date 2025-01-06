<?php
/* fetchDataRetur */

require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';
require_once '../class/mutasi.php';
require_once '../class/barang.php';
require_once '../class/masuk.php';
require_once '../class/saldo.php';

$detailSaldoClass = new DetailSaldo($koneksi);
$classMutasi = new Mutasi($koneksi);
$barangClass = new Barang($koneksi);
$masukClass = new Masuk($koneksi);
$saldoClass = new Saldo($koneksi);

$conn = $koneksi;


$valid['success'] =  array('success' => false, 'messages' => array());

try {
    $result = handleProsesMutasi($classMutasi, $barangClass, $masukClass, $saldoClass, $detailSaldoClass, $conn);
    if ($result['success']) {
        $valid['success'] = true;
        $valid['messages'] = "<strong>Success! </strong> " . $result['message'];
    } else {
        throw new Exception("Tidak Ada Yang Eksekusi");
    }
} catch (Exception $e) {
    $valid['success'] = false;
    $errorMessage = $e->getMessage();
    $valid['messages'] = "<strong>Error! </strong> " . (empty($errorMessage) ? "Terjadi Kesalahan" : $errorMessage);
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleProsesMutasi($classMutasi, $barangClass, $masukClass, $saldoClass, $detailSaldoClass, $conn)
{
    $conn->begin_transaction();
    $dataMutasi = handleDataMutasi($classMutasi);
    if (!$dataMutasi['success']) {
        return $dataMutasi;
    }

    $noMutasi = generateNoMutasi($masukClass);
    if (!$noMutasi['success']) {
        return $noMutasi;
    }

    $dataProsess = []; //debuging
    $idMasuk = handleMasuk($masukClass, $noMutasi['data'], $_SESSION['nama']);
    foreach ($dataMutasi['data'] as $key => $value) {
        $data = getInputs($value);
        $data['id_msk'] = $idMasuk;

        $result = prosesMutasi($classMutasi, $barangClass, $masukClass, $saldoClass, $detailSaldoClass, $conn, $data);
        if (!$result['success']) {
            $conn->rollback();
            return $result;
        }
        $dataProsess[] = $result;
    }
    /* var_dump($dataProsess);
    die(); */
    $conn->commit();
    return ['success' => true, 'message' => "Data Berhasil Di Mutasi"];
}


function prosesMutasi($classMutasi, $barangClass, $masukClass, $saldoClass, $detailSaldoClass, $conn, $data)
{

    if ($data['idrak_asal'] == $data['idrak_tujuan']) {
        throw new Exception("Lokasi Pengirim Tidak Boleh Sama Dengan Lokasi Penerima");
    }

    $checkItem = $barangClass->getItemById($data['id_brg'], $data['idrak_tujuan']);
    if ($checkItem->num_rows == 1) {
        $dataItem = $checkItem->fetch_assoc();
        $id = $dataItem['id'];
    } elseif ($checkItem->num_rows == 0) {
        $id = handleNewItem($barangClass, $conn, $data['id_brg'], $data['idrak_tujuan']);
    } else {
        $conn->rollback();
        throw new Exception("Barang Duplikat");
    }

    $insertMasukDetail = handleMasukDetail($masukClass, $conn, $data, $id);
    $handleCheckSaldo = handleCheckSaldo($saldoClass, $detailSaldoClass, $classMutasi, $conn, $id, $data);

    if ($insertMasukDetail['success'] && $handleCheckSaldo['success']) {
        return ['success' => true, 'message' => "Data Berhasil Di Mutasi"];
    }

    throw new Exception("Data Gagal Dimutasi");

    /* var_dump($checkItem);
    die(); */
}

function handleCheckSaldo($saldoClass, $detailSaldoClass, $classMutasi, $conn, $id, $data)
{
    $checkSaldoNew = $saldoClass->getSaldoByid($id, date('m'), date('Y'));
    $checkSaldoOld = $saldoClass->getSaldoByid($data['id_asal'], date('m'), date('Y'));
    $checkDetailSaldo = $detailSaldoClass->getDetailSaldoByidDetailsaldo($data['idDetailSaldo']);


    $resultSaldoNew = $checkSaldoNew->fetch_assoc();
    $resultSaldoOld = $checkSaldoOld->fetch_assoc();
    $resultDetailSaldo = $checkDetailSaldo->fetch_assoc();

    if ($checkSaldoOld->num_rows == 0) {
        throw new Exception("Data Saldo Lama Tidak Ditemukan. Di Tabel Saldo");
    }

    if ($resultSaldoOld['saldo_akhir'] < $data['qty'] || $resultDetailSaldo['jumlah'] < $data['qty']) {
        throw new Exception("Saldo Barang Tidak Cukup");
    }

    $idSaldoOld = $resultSaldoOld['id_saldo'];
    $totalSaldoOld = $resultSaldoOld['saldo_akhir'] - $data['qty'];

    $tgl = date('Y-m-d');

    if ($checkSaldoNew->num_rows == 1) {
        $totalSaldoNew = $resultSaldoNew['saldo_akhir'] + $data['qty'];
        return handleUpdateSaldo($saldoClass, $detailSaldoClass, $classMutasi, $conn, $resultSaldoNew['id_saldo'], $id, $data['tahunprod'], $data['qty'], $totalSaldoNew, $idSaldoOld, $totalSaldoOld, $resultDetailSaldo['jumlah'], $data['idDetailSaldo'], $data);
    } elseif ($checkSaldoNew->num_rows == 0) {
        return handleNewSaldo($saldoClass, $detailSaldoClass, $classMutasi, $conn, $id, $tgl, $data, $idSaldoOld, $totalSaldoOld, $resultDetailSaldo['jumlah']);
        //return handleNewSaldo($saldoClass, $detailSaldoClass, $id, $tgl, $data['tahunprod'], $data['qty'], $idSaldoOld, $totalSaldoOld, $resultDetailSaldo['jumlah'], $data['idDetailSaldo']);
    } else {
        $valid['success'] = false;
        $valid['messages'] = "<strong>Error! </strong> Data Saldo Duplikat. Di Tabel Saldo ";
        return $valid;
    }
}


function handleDetailSaldo($detailSaldoClass, $conn, $id, $tahunprod, $jml, $jumlahDetaiLSaldo, $idDetailSaldoOld)
{
    try {
        $checkDetailSaldo = $detailSaldoClass->getDetailSaldoByidAndYearProd($id, $tahunprod);
    } catch (Exception $e) {
        $conn->rollback();
        throw new Exception("Data Detail Slado Gagal Diambil. Di Tabel Saldo");
    }

    $updateDetailSaldoOld = $detailSaldoClass->updateMinus($idDetailSaldoOld, $jml);

    if (!$updateDetailSaldoOld['success']) {
        $conn->rollback();
        throw new Exception("Data Gagal Disimpan. Di Tabel Detail Saldo");
    }

    if ($checkDetailSaldo->num_rows == 0) {
        $insertDetailSaldo = $detailSaldoClass->save($id, $tahunprod, $jml);

        if (!$insertDetailSaldo['success']) {
            $conn->rollback();
            throw new Exception("Data Gagal Disimpan. Di Tabel New Detail Saldo");
        }

        return $insertDetailSaldo;
    } elseif ($checkDetailSaldo->num_rows == 1) {
        $resultDetailSaldo = $checkDetailSaldo->fetch_array();
        $idDetailSaldo = $resultDetailSaldo['id_detailsaldo'];
        $updateDetailSaldo = $detailSaldoClass->updatePlus($idDetailSaldo, $jml);

        if (!$updateDetailSaldo['success']) {
            $conn->rollback();
            throw new Exception("Data Gagal Disimpan. Di Tabel Detail Saldo Plus");
        }

        return $updateDetailSaldo;
    } else {
        $conn->rollback();
        throw new Exception("Data Duplikat. Di Tabel Detail Saldo");
    }
}

function handleNewSaldo($saldoClass, $detailSaldoClass, $classMutasi, $conn, $id, $tgl, $data, $idSaldoOld, $totalSaldoOld, $jumlahDetaiLSaldo)
{

    try {
        $insertSaldo = $saldoClass->save($id, $tgl, $data['qty']);
        $updateSaldoOld = $saldoClass->update($idSaldoOld, $totalSaldoOld);
        $detailSaldo = handleDetailSaldo($detailSaldoClass, $conn, $id, $data['tahunprod'], $data['qty'], $jumlahDetaiLSaldo, $data['idDetailSaldo']);
    } catch (Exception $e) {
        $conn->rollback();
        throw new Exception("Saldo Baru Error. Di Tabel Saldo");
    }

    if ($insertSaldo['success'] && $detailSaldo['success'] && $updateSaldoOld['success']) {
        $mutasi = handleUpdateMutasi($classMutasi, $conn, $data['id_mutasi']);

        if (!$mutasi['success']) {
            $conn->rollback();
            throw new Exception("Data Gagal Disimpan. Di Tabel Detail Saldo");
        }

        $conn->commit();
        return ['success' => true, 'messages' => 'Data Berhasil Dimutasi'];
    }

    $conn->rollback();

    throw new Exception("Data Gagal Disimpan. Di Tabel Saldo");
}

function handleUpdateSaldo($saldoClass, $detailSaldoClass, $classMutasi, $conn, $idSaldo, $id, $tahunprod, $jml, $saldoAkhir, $idSaldoOld, $totalSaldoOld, $jumlahDetaiLSaldo, $idDetailSaldoOld, $data)
{
    try {
        $updateSaldo = $saldoClass->update($idSaldo, $saldoAkhir);
        $updateSaldoOld = $saldoClass->update($idSaldoOld, $totalSaldoOld);

        $detailSaldo = handleDetailSaldo($detailSaldoClass, $conn, $id, $tahunprod, $jml, $jumlahDetaiLSaldo, $idDetailSaldoOld);
    } catch (Exception $e) {
        $conn->rollback();
        throw new Exception("Uupdate Saldo Error. Di Tabel Saldo");
    }

    if ($updateSaldo['success'] && $updateSaldoOld['success'] && $detailSaldo['success']) {
        $mutasi = handleUpdateMutasi($classMutasi, $conn, $data['id_mutasi']);

        if (!$mutasi['success']) {
            $conn->rollback();
            throw new Exception("Data Gagal Disimpan. Di Tabel Detail Saldo");
        }

        return ['success' => true, 'messages' => 'Data Berhasil Dimutasi'];
    }

    $conn->rollback();
    throw new Exception("Data Gagal Diupdate. Di Tabel Saldo & Detail Saldo");
}

function handleMasukDetail($masukClass, $conn, $data, $id)
{
    $jam               = date("H:i:s");
    $insertMasukDetail = $masukClass->saveDetail($data['id_msk'], $id, $jam, $data['qty'], $data['ket'], '0', $data['rak_asal']);
    $insertTahunProd = $masukClass->saveTahunProd($insertMasukDetail['id'], $data['tahunprod']);

    if ($insertMasukDetail['success'] && $insertTahunProd['success']) {
        return ['success' => true, 'id' => $insertMasukDetail['id']];
    }

    $conn->rollback();

    throw new Exception("Data Gagal Disimpan. Di Tabel Detail Masuk ");
}


function handleMasuk($masukClass, $noMutasi, $user)
{
    $tgl = date('Y-m-d');
    $insertMasuk = $masukClass->save($tgl, $noMutasi, $user, 3);

    if (!$insertMasuk['success']) {
        throw new Exception("Data Gagal Disimpan. Di Tabel Masuk");
    }

    return $insertMasuk['id'];
}

function handleNewItem($barangClass, $conn, $id_brg, $id_rak)
{

    $insertDetailItem = $barangClass->saveDetail($id_brg, $id_rak);

    if (!$insertDetailItem['success']) {
        $conn->rollback();
        throw new Exception("Data Gagal Disimpan. Di Tabel Detail Barang");
    }

    return $insertDetailItem['id'];
}
/*array(11) {
  [""]
  ["id_brg"]=>
  string(3) "359"
  ["id_asal"]=>
  string(4) "7352"
  ["idrak_asal"]=>
  string(1) "1"
  ["rak_asal"]=>
  string(4) "A1.1"
  ["idrak_tujuan"]=>
  string(2) "29"
  ["rak_tujuan"]=>
  string(4) "A8.1"
  ["idDetailSaldo"]=>
  string(1) "1"
  ["qty"]=>
  string(1) "2"
  ["ket"]=>
  string(8) "septiana"
  ["noMutasi"]=>
  string(13) "MG2411-000111"
  ["user"]=>
  string(5) "claim"
  ["id_msk"]=>
  int(13855)
}
*/


function getInputs($data)
{
    $inputs = [
        "id_mutasi"         => trim($data['id_mutasi']),
        "id_brg"            => trim($data['id_brg']),
        "id_asal"           => trim($data['id_asal']),
        "idrak_asal"        => trim($data['idrak_asal']),
        "rak_asal"          => trim($data['rak_asal']),
        "idrak_tujuan"      => trim($data['idrak_tujuan']),
        "rak_tujuan"        => trim($data['rak_tujuan']),
        "idDetailSaldo"     => trim($data['id_detailsaldo']),
        "tahunprod"         => trim($data['tahunprod']),
        "qty"               => trim($data['qty']),
        "ket"               => trim($data['user'])
    ];

    return $inputs;
}

function handleUpdateMutasi($classMutasi, $conn, $id_mutasi)
{
    $data = [
        "id" => $id_mutasi,
        "date" => date("Y-m-d H:i:s")
    ];
    $result = $classMutasi->update($data);
    if (!$result['success']) {
        throw new Exception("Data Gagal Update . Di Tabel TMP Mutasi");
    }

    return ['success' => true, 'messages' => 'Data Berhasil Dimutasi'];
}

/* call data mutasi */
function handleDataMutasi($classMutasi)
{
    $result = $classMutasi->fetchAll();
    if ($result->num_rows == 0) {
        throw new Exception("Data Mutasi Tidak Ada Data");
    }
    $data = $result->fetch_all(MYSQLI_ASSOC);
    return ['success' => true, 'data' => $data];
}

/* generate no mutasi */
function generateNoMutasi($masukClass)
{
    $retur = '3';
    $result = $masukClass->getLastNoSuratJln($retur);
    if ($result->num_rows == 0) {
        return ['success' => true, 'data' => "MG" . date('ym') . "-000001"];
    }
    $data = $result->fetch_assoc();
    $no = $data['suratJln'];
    $no = explode("-", $no)[1];
    $no = intval($no);
    $no = $no + 1;
    $date = date('ym');
    $no = str_pad($no, 6, "0", STR_PAD_LEFT);
    $noMutasi = "MG" . $date . "-" . $no;

    return ['success' => true, 'data' => $noMutasi];
}
