<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/pomasuk.php';

$responseArray = getScanMasuk($_GET['id'], $koneksi);

echo json_encode($responseArray);
function getScanMasuk($id_pomsk, $koneksi)
{
    $pomasuk = new PoMasuk($koneksi);
    $result = $pomasuk->getPoMasukById($id_pomsk);
    $row = $result->fetch_array();

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "http://192.168.1.21:4001/incoming/" . $row['id_msk'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ]);

    try {
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception(curl_error($curl));
        }

        $response =  json_decode($response);

        $resultPoMasukDetail = $pomasuk->getPoMasukDetailById($row['id_msk']);
        $ids_to_filter = [];
        while ($rowPoMasukDetail = $resultPoMasukDetail->fetch_array()) {
            $ids_to_filter[] = $rowPoMasukDetail['id_masuk_det'];
        }
        //$ids_to_filter = [1]; // Replace with your list of ids

        $filteredResult = array_filter($response->data, function ($item) use ($ids_to_filter) {
            return !in_array($item->id_masuk_det, $ids_to_filter);
        });
        $form = generateForm($filteredResult);

        $newResponse = [
            'status' => 'success',
            'data' => $response->data,
            'filter' => $ids_to_filter,
        ];

        return $newResponse;
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    } finally {
        curl_close($curl);
    }
}

function generateForm($data)
{
    $form = "<form method='post' action='action/barangMasuk/simpanMasuk.php'>";
    foreach ($data as $value) {
        $date = new DateTime($value->tanggal_masuk);
        $formattedDate = $date->format('Y-m-d');
        $form .= "<tr>";
        $form .= "<input type='hidden' name='suratJLN[]' value='" . $value->suratJalan . "' />";
        $form .= "<input type='hidden' name='id_brg[]' value='" . $value->id_item . "' />";
        $form .= "<input type='hidden' name='id_rak[]' value='" . $value->id_rak . "' />";
        $form .= "<input type='hidden' name='tgl[]' value='" . $formattedDate . "' />";
        $form .= "<td>" . $value->item . "</td>";
        $form .= "<td  width='10%'>" . $value->rak . "</td>";
        $form .= "<td  width='4%'><input type='text' name='jml[]' value='" . $value->qty . "' class='input-small'/></td>";
        $form .= "<td  width='8%'><input type='text' name='ket[]' class='input-small'/></td>";
        $form .= "<td  width='5%'><button type='submit' class='btn btn-primary'>Simpan</button></td>";
        $form .= "</tr>";
    }
    $form .= "</form>";
    return $form;
}
