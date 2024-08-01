<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';

$soClass = new Salesorder($koneksi);

try {
    $nopol = trim($koneksi->real_escape_string($_POST['nopol']));
    if (!isset($nopol) || empty($nopol)) {
        header('location:../../uploadsalesorder.php');
    }

    $result = handlePrintSalesOrder($soClass, $nopol);

    $completeTable = generateCompleteTable($result);
    echo $completeTable;
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
}

function compareRak($a, $b)
{
    preg_match('/([A-Z])(\d+)\.(\d+)/', $a, $matchesA);
    preg_match('/([A-Z])(\d+)\.(\d+)/', $b, $matchesB);

    // Bandingkan huruf
    if ($matchesA[1] != $matchesB[1]) {
        return strcmp($matchesA[1], $matchesB[1]);
    }

    // Bandingkan angka pertama
    if (intval($matchesA[2]) != intval($matchesB[2])) {
        return intval($matchesA[2]) - intval($matchesB[2]);
    }

    // Bandingkan angka setelah titik
    return intval($matchesA[3]) - intval($matchesB[3]);
}



function groupSaldoByKdbrg($data)
{
    $groupedData = [];

    while ($row = $data->fetch_assoc()) {
        $nopol = $row['nopol'];
        if (!isset($groupedData[$nopol])) {
            $groupedData[$nopol] = [
                'nopol' => $nopol,
                'supir' => $row['supir'],
                'jenis' => $row['jenis'],
                'details' => []
            ];
        }
        $groupedData[$nopol]['details'][] = [
            'toko' => $row['toko'],
            'brg' => $row['brg'],
            'rak' => $row['rak'],
            'tahunprod' => $row['tahunprod'],
            'qty_pro' => $row['qty_pro']
        ];
    }

    // Urutkan details berdasarkan rak untuk setiap nopol
    foreach ($groupedData as &$group) {
        usort($group['details'], function ($a, $b) {
            return compareRak($a['rak'], $b['rak']);
        });
    }

    return array_values($groupedData);
}

function handlePrintSalesOrder($soClass, $nopol)
{
    $result = $soClass->getDataDetailProsessSalesOrder($nopol);
    $groupedData = groupSaldoByKdbrg($result);

    return $groupedData;
}

function generateCompleteTable($data)
{
    $style = '
    <style type="text/css">
    *{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
    }
        .control-label {
            text-align: left;
            width: 300px;
        }
        label {
            display: block;
            margin-bottom: -10px; 
            font-size: 14px;
            font-weight: normal;
            line-height: 20px;
        }
        strong {
            font-weight: normal;
        }
        .titik2 {
            padding-left: 130px;
            margin-top: -20px;
            font-weight: bold;
        }
        #nota th, td {
            padding: 1px;
            margin: 1px;
            font-size: 14px;
        }
        label.oleh {
            position: absolute;
            margin-top: -145px;
        }
        label.tgl {
            position: absolute;
            margin-top: -122px;
        }
        label.noReg {
            position: absolute;
            margin-top: -99px;
        }
        .isi {
            padding-left: 82px;
            margin-top: -20px;
            font-weight: normal;
        }
        .mar {
            margin-top:-15px;
        }
        #mar {
            margin-top:-20px;
        }
        .textHeader {
            margin-bottom: 0px;
        }
        .childHeader {
            margin-top: 2px;
            padding-top: 0px;
            margin-bottom: 0px;
        }
        .table-striped {
            width: 100%;
            border-collapse: collapse;
        }
        .table-striped th {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: left;
            padding: 8px;
        }
        .table-striped th {
            text-align: center;
        }
        .table-striped td {
            padding: 2px;
        }
        .table-striped tr:nth-child(even) {
            background-color: #ffffff;
        }
        .table-striped tr:nth-child(odd) {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
    </style>';

    $html = $style;

    foreach ($data as $row) {
        $html .= '
        <table rules="none" border="1" width="100%">
            <tr>
                <td>
                    <center>
                        <h4 class="textHeader">SALES ORDER GUDANG</h4>
                        <h4 class="childHeader">' . htmlspecialchars($row['jenis']) . ' - ' . htmlspecialchars($row['nopol']) . ' - ' . htmlspecialchars($row['supir']) . '</h4>
                        <h4 class="childHeader">' . date('d-m-Y') . '</h4>
                    </center>
                    <br />
                </td>
            </tr>
            <table class="table-striped" border="1" cellspacing="0" cellpadding="1" width="100%" id="nota">
                <tr>
                    <th width="30%">Toko</th>
                    <th>Barang</th>
                    <th width="8%">Rak</th>
                    <th width="10%">Tahun</th>
                    <th width="7%">Qty</th>
                </tr>
                ';

        $rowCount = 0;
        foreach ($row['details'] as $detail) {
            $rowCount++;
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars(substr($detail['toko'], 0, 25)) . '</td>';
            $html .= '<td>' . htmlspecialchars(substr($detail['brg'], 0, 46)) . '</td>';
            $html .= '<td class="text-center ">' . htmlspecialchars($detail['rak']) . '</td>';
            $html .= '<td class="text-center ">' . htmlspecialchars($detail['tahunprod']) . '</td>';
            $html .= '<td class="text-center ">' . htmlspecialchars($detail['qty_pro']) . '</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';

        $html .= '
            <table rules="none" border="1" width="100%">
                <tr>
                    <th width="33%">Checker</th>
                    <th width="33%">Admin Gudang</th>
                    <th width="33%">Kepala Gudang</th>
                </tr>
                <tbody>
                    <tr>
                        <td style="padding-top:35px; text-align:center;">............................</td>
                        <td style="padding-top:35px; text-align:center;">............................</td>
                        <td style="padding-top:35px; text-align:center;">............................</td>
                    </tr>	
                </tbody>
            </table>
        </table>';
    }

    return $html;
}
