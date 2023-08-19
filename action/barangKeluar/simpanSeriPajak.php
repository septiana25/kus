<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';

$valid['success'] = array('success' => false, 'messages' => array());


if (isset($_POST))
{
	$seriPajakLama = $koneksi->real_escape_string($_POST['noSeriPJKLama']);
	$seriPajakBaru = $koneksi->real_escape_string($_POST['noSeriPJKBaru']);

	$updateSeriPajak = "UPDATE tblSeriPajak SET seriPajak = '$seriPajakBaru' WHERE seriPajak = '$seriPajakLama'";

	if ($koneksi->query($updateSeriPajak))
	{
		$valid['success']  = true;
		$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan ";
	}
	else
	{
		$valid['success']  = false;
		$valid['messages'] = '<strong>Error! </strong> Data Gagal Disimpan '.$koneksi->error;
	}

$koneksi->close();

echo json_encode($valid);
	
}
