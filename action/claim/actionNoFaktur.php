<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

	$id_klr = $_POST['id_klr'];
	$query = "SELECT id_brg, brg FROM detail_keluar JOIN detail_brg USING(id) JOIN barang USING(id_brg) WHERE id_klr=$id_klr";
	$result = $koneksi->query($query);
	//$output = array('data' => array());

		foreach ($result as $row) {
		?>
			<option value="<?php echo $row["id_brg"]; ?>"><?php echo $row["brg"] ?></option>
		<?php
		}
?>