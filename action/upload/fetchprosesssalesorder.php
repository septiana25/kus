<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../class/salesorder.php';

$soClass = new Salesorder($koneksi);

try {
    $result = handleFetchProsessSalesOrder($soClass);

    echo json_encode($result);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $koneksi->close();
}

function simpleEncrypt($data, $key = 'ianseptiana')
{
    $result = '';
    for ($i = 0; $i < strlen($data); $i++) {
        $char = substr($data, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result .= $char;
    }
    return base64_encode($result);
}


function generateButton($expedition)
{
    $encryptExpedition = urlencode(simpleEncrypt($expedition));
    $button = '<div class="btn-group">
        <button data-toggle="dropdown" class="btn btn-small btn-primary dropdown-toggle">Action <span class="caret"></span></button>
        <ul class="dropdown-menu">
            <li><a href="detailprosesssalesorder.php?expedition=' . $encryptExpedition . '"><i class="fa fa-eye" aria-hidden="true"></i> Detail</a></li>
        </ul>
    </div>';

    return $button;
}

function generateLabel($value, $defaultText = 'Gantung', $dangerClass = 'important')
{
    if (is_null($value) || empty($value)) {
        return "<span class=\"label label-{$dangerClass}\">{$defaultText}</span>";
    }
    return $value;
}

function handleFetchProsessSalesOrder($soClass)
{
    $result = $soClass->getDataProsessSalesOrder();
    $output = array('data' => array());

    while ($row = $result->fetch_array()) {
        $button = generateButton($row['supir']);

        $output['data'][] = array(
            $row['supir'],
            $row['tgl'],
            $row['faktur'],
            generateLabel($row['no_nota']),
            $button
        );
    }

    return $output;
}
