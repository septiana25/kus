<?php
require_once 'function/session.php';
require_once 'action/class/pomasuk.php';

function getScanMasuk($id_pomsk, $koneksi)
{

    $pomasuk = new PoMasuk($koneksi);
    $result = $pomasuk->getPoMasukById($id_pomsk);
    $row = $result->fetch_array();

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "http://localhost:4001/incoming/" . $row['id_msk'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);



    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        return json_decode($response);
    }

    curl_close($curl);
}
