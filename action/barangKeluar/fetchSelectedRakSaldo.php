<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/detailsaldo.php';
require_once '../class/saldo.php';

$saldoClass = new Saldo($koneksi);


try {
    $idBrg = trim($koneksi->real_escape_string($_POST['id_brg']));
    $checkSaldoLastDate    = $saldoClass->getSaldoByLastDate();
    $monthSaldoLastDate = SUBSTR($checkSaldoLastDate, 5, -3);
    $yearSaldoLastDate = SUBSTR($checkSaldoLastDate, 0, -6);
    $result = $saldoClass->getSaldoByidJoinDetailByDate($idBrg, $monthSaldoLastDate, $yearSaldoLastDate);

    $list = "<option value='0'>Pilih Lokasi..</option>";
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $year = substr($row['tahunprod'], 2, 4);
        $week = substr($row['tahunprod'], 0, 2);
        $start = date("Y-m-d", strtotime("01 Jan 20" . $year . " 00:00:00 GMT + " . $week . " weeks"));
        $data[] = [
            "id_detailsaldo" => $row['id_detailsaldo'],
            "rak" => $row['rak'],
            "tahunprod" => $row['tahunprod'],
            "jumlah" => $row['jumlah'],
            "start" => $start
        ];
        //$list .= "<option value='" . $row['id_detailsaldo'] . "'>" . $row['rak'] . " tahun: " . $row['tahunprod'] . " saldo: " . $row['jumlah'] . "</option>";
    }
    usort($data, function ($a, $b) {
        return strtotime($a['start']) - strtotime($b['start']);
    });

    foreach ($data as $row) {
        $list .= "<option value='" . $row['id_detailsaldo'] . "'>" . $row['rak'] . " tahun: " . $row['tahunprod'] . " saldo: " . $row['jumlah'] . "</option>";
    }
    echo $list;
} catch (\Throwable $th) {
    echo json_encode(["error" => "An error occurred while fetching Plat Nomor."]);
} finally {
    $koneksi->close();
}
