<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';
require_once '../class/promosi.php';

$promosiClass = new Promosi($koneksi);

try {
    $no_trank = trim($koneksi->real_escape_string($_POST['noTrans']));
    //$no_trank = 'BK2410-0000002';
    if (!isset($no_trank) || empty($no_trank)) {
        header('location:../../promosikeluar.php');
    }

    $result = handlePrintNotaPromosiKeluar($promosiClass, $no_trank);

    $completeTable = generateCompleteTable($result);
    echo $completeTable;
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
}



function groupNoTransaksi($data)
{
    $groupedData = [];

    while ($row = $data->fetch_assoc()) {
        $no_trank = $row['no_trank'];
        if (!isset($groupedData[$no_trank])) {
            $groupedData[$no_trank] = [
                'no_trank' => $no_trank,
                'toko' => $row['toko'],
                'sales' => $row['sales'],
                'alamat' => $row['alamat'],
                'divisi' => $row['divisi'],
                'details' => []
            ];
        }
        $groupedData[$no_trank]['details'][] = [
            'item' => $row['item'],
            'qty' => $row['qty'],
            'note' => $row['note'],
            'at_create' => $row['at_create']
        ];
    }

    return $groupedData;
}

function handlePrintNotaPromosiKeluar($promosiClass, $no_trank)
{
    $result = $promosiClass->getPromosiByNoTranPrint($no_trank);
    $groupedData = groupNoTransaksi($result);

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
            margin-top: 10px;
            margin-bottom: 0px;
            font-size: 18px;
        }

        .textHeader1 {
            margin-top: 0px;
            margin-bottom: 0px;
            font-size: 16px;
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
        // Cetak laporan dua kali
        for ($i = 0; $i < 2; $i++) {
            $html .= '
            <table rules="none" border="1" width="100%">
                <tr>
                    <td>
                        <center>
                            <h1 class="textHeader">PT. KHARISMA UTAMA SENTOSA</h1>
                            <h4 class="childHeader">Jl. Satria Raya 2 No. 9 Kota Bandung</h4>
                            <hr style="border-top: 1px solid black; width: 100%; margin: 10px 0;">
                            <h2 class="textHeader1">BUKTI TERIMA BARANG PROMOSI</h2>
                        </center>
                        <br />
                    </td>
                </tr>
                <tr>
                    <td>
                        <h4 style="margin-bottom: 0px; display: flex; justify-content: space-between;">
                            <span style="min-width: 80px;">No</span>
                            <span>:</span>
                            <span style="flex-grow: 1; padding-left: 10px;">' . htmlspecialchars($row['no_trank']) . '</span>
                        </h4>
                        <h4 style="margin-bottom: 0px; margin-top: 0px; display: flex; justify-content: space-between;">
                            <span style="min-width: 80px;">Toko</span>
                            <span>:</span>
                            <span style="flex-grow: 1; padding-left: 10px;">' . htmlspecialchars($row['toko']) . '</span>
                        </h4>
                        <h4 style="margin-top: 2px; display: flex; justify-content: space-between;">
                            <span style="min-width: 80px;">Alamat</span>
                            <span>:</span>
                            <span style="flex-grow: 1; padding-left: 10px;">' . htmlspecialchars($row['alamat']) . '</span>
                        </h4>
                    </td>
                </tr>
                <table class="table-striped" border="1" cellspacing="0" cellpadding="1" width="100%" id="nota">
                    <tr>
                        <th>Nama Barang</th>
                        <th width="8%">Qty</th>
                        <th width="30%">Note</th>
                    </tr>
                    ';

            foreach ($row['details'] as $detail) {
                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($detail['item']) . '</td>';
                $html .= '<td class="text-center ">' . htmlspecialchars($detail['qty']) . '</td>';
                $html .= '<td>' . htmlspecialchars($detail['note']) . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';

            $html .= '
                <table rules="none" border="1" width="100%">
                    <tr>
                        <th width="33%">Pembuat,</th>
                        <th width="33%">Mengetahui,</th>
                        <th width="33%">Menerima,</th>
                    </tr>
                    <tbody>
                        <tr>
                            <td style="padding-top:35px; text-align:center;">............................</td>
                            <td style="padding-top:35px; text-align:center;">............................</td>
                            <td style="padding-top:35px; text-align:center;">............................</td>
                        </tr>
                        <tr>
                            <td style="text-align:center;">Romy</td>
                            <td style="text-align:center;">Ka.Gudang</td>
                            <td style="text-align:center;">' . htmlspecialchars($row['toko']) . '</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="padding-top: 20px;">Note: Mohon 1 lembar bukti terima barang dikembalikan kepada sales/driver</td>
                        </tr>
                        <tr>
                            <td colspan="3">Dicetak : ' . htmlspecialchars($_SESSION['nama']) . ' ' . date('d-m-Y H:i:s') . '</td>
                        </tr>
                    </tbody>
                </table>
            </table>';

            // Tambahkan pemisah halaman jika ini adalah cetakan pertama
            if ($i == 0) {
                $html .= '<br>';
            }
        }
    }

    return $html;
}
