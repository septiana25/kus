<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';

if ($_POST) {
	$id_post = $koneksi->real_escape_string($_POST['id_post']);

	$query = "SELECT no_toll, pemegang, no_pol, a1.id_post as no_pos
		FROM(	
			SELECT id_toll, no_toll, pemegang, no_pol, id_post FROM  tblTransToll
			JOIN tblDetTransToll USING(id_trans)
			JOIN tblEToll USING(id_toll)
			JOIN tblPostingEToll AS post USING(id_trans) WHERE id_post = $id_post
		)a1
		LEFT JOIN (
			SELECT id_toll, id_tmbh, tmbh_saldo, id_post FROM tblTmbhSaldo
			JOIN tblPostingEToll USING(id_tmbh) WHERE id_post = $id_post
			
		)a2 ON a1.id_toll=a2.id_toll GROUP BY a1.id_post, a2.id_post";

	$result = $koneksi->query($query);

	if ($result->num_rows > 0) {
		$row = $result->fetch_assoc();
	}

	$koneksi->close();

	echo json_encode($row);
}
?>