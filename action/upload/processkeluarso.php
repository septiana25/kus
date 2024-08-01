<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/keluar.php';
require_once '../class/salesorder.php';

$keluarClass = new Keluar($koneksi);
$soClass = new SalesOrder($koneksi);

$valid['success'] =  array('success' => false, 'messages' => array());

$conn = $koneksi;

try {
    $nopol = trim($koneksi->real_escape_string($_POST['nopol']));

    $resultProsesKeluar = handleProsessKeluarSO($keluarClass, $soClass, $nopol, $conn);

    if (!$resultProsesKeluar['success']) {
        $valid['success'] = false;
        $valid['messages'] = $resultProsesKeluar['messages'];
    } else {
        $valid['success'] = true;
        $valid['messages'] = $resultProsesKeluar['messages'];
    }
} catch (\Throwable $th) {
    $valid['success'] = false;
    $valid['messages'] = "<strong>Error! </strong> Data Ada Yang Gagal";
} finally {
    $koneksi->close();
    echo json_encode($valid);
}

function handleProsessKeluarSO($keluarClass, $soClass, $nopol, $conn)
{
    $dataProsesSO = $soClass->getDataDetailProsessSOForKeluar($nopol);
    $results = [
        'success' => true,
        'messages' => []
    ];
    $dataProsesSO = groupSaldoByKdbrg($dataProsesSO);

    if (count($dataProsesSO) == 0) {
        $results['success'] = false;
        $results['messages'][] = "<strong>Error! </strong> Data Tidak Ditemukan";
        return $results;
    }

    foreach ($dataProsesSO as $saldoItem) {

        $conn->begin_transaction();
        $idKlr = handleInsertKeluar($keluarClass, $saldoItem['no_faktur'], $saldoItem['id_toko'], $_SESSION['nama'], $saldoItem['supir']);
        if (!$idKlr) {
            $conn->rollback();
            $results['success'] = false;
            $results['messages'][] = "<strong>Error! </strong> Data Ada Yang Gagal {$saldoItem['no_faktur']}";
            continue;
        }

        foreach ($saldoItem['details'] as $detail) {
            $resultInsertDetailKeluar = handleInsertDetailKeluar($keluarClass, $idKlr['id'], $detail['id'], $detail['qty'], $detail['note']);
            if (!$resultInsertDetailKeluar['success']) {
                $conn->rollback();
                $results['success'] = false;
                $results['messages'][] = "<strong>Error! </strong> Data Detail Keluar Gagal {$detail['id_pro']}";
                continue;
            }

            $resultInsertKeluarTahunProd = handleInsertDetailKeluarTahunProd($keluarClass, $resultInsertDetailKeluar['id'], $detail['tahunprod']);
            if (!$resultInsertKeluarTahunProd['success']) {
                $conn->rollback();
                $results['success'] = false;
                $results['messages'][] = "<strong>Error! </strong> Data Tahun Produksi Gagal {$detail['id_pro']}";
                continue;
            }

            $nota = 'KL-' . $saldoItem['supir'] . '-' . date('Ymd');
            $resultUpdateNoNota = handleUpdateNoNota($soClass, $detail['id_pro'], $nota);
            if (!$resultUpdateNoNota['success']) {
                $conn->rollback();
                $results['success'] = false;
                $results['messages'][] = "<strong>Error! </strong> Data No Nota Gagal {$detail['id_pro']}";
                continue;
            }
            $conn->commit();
            $results['messages'][] = "<strong>Success! </strong> Data Selesai Diproses Keluar";
        }
    }
    return $results;
}

function handleInsertKeluar($keluarClass, $noFaktur, $id_toko, $nama, $supir)
{
    $result = $keluarClass->save(date('Y-m-d'), $noFaktur, $id_toko,  $nama, $supir);
    return $result;
}

function handleInsertDetailKeluar($keluarClass, $idKlr, $id, $jmlKlr, $note)
{
    $jam = date('H:i:s');
    $result = $keluarClass->saveDetail($idKlr, $id, $jmlKlr, $jam, $jmlKlr, $note, '0');
    return $result;
}

function handleInsertDetailKeluarTahunProd($keluarClass, $idDetail, $tahunprod)
{
    $result = $keluarClass->saveTahunProd($idDetail, $tahunprod);
    return $result;
}

function handleUpdateNoNota($soClass, $id_pro, $no_nota)
{
    $result = $soClass->updateNoNotaProssesSalesOrder($id_pro, $no_nota);
    return $result;
}

function groupSaldoByKdbrg($dataProsesSO)
{
    $groupedData = [];

    while ($row = $dataProsesSO->fetch_assoc()) {
        $noFaktur = $row['no_faktur'];
        if (!isset($groupedData[$noFaktur])) {
            $groupedData[$noFaktur] = [
                'supir' => $row['supir'],
                'no_faktur' => $noFaktur,
                'id_toko' => $row['id_toko'],
                'details' => []
            ];
        }
        $groupedData[$noFaktur]['details'][] = [
            'id_pro' => $row['id_pro'],
            'id_detailsaldo' => $row['id_detailsaldo'],
            'id' => $row['id'],
            'tahunprod' => $row['tahunprod'],
            'qty' => $row['qty_pro'],
            'note' => $row['note']
        ];
    }

    return array_values($groupedData);
}
