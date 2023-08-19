<?php
require_once '../../function/koneksi.php';
require_once '../../function/session.php';
require_once '../../function/setjam.php';

	$valid['success'] = array('success' => false, 'messages' => array());

	if ($_POST) {
		$tgl       = date('Y-m-d', strtotime($_POST['tgl']));
		$no_claim   = $koneksi->real_escape_string($_POST['no_claim']);
		$pengaduan   = $koneksi->real_escape_string($_POST['pengaduan']);
		$daerah    = $koneksi->real_escape_string($_POST['daerah']);
		$dealer    = $koneksi->real_escape_string($_POST['dealer']);
		$toko      = $koneksi->real_escape_string($_POST['toko']);
		$sales     = $koneksi->real_escape_string($_POST['sales']);
		$id_brg    = $koneksi->real_escape_string($_POST['brg']);
		$pattern   = $koneksi->real_escape_string($_POST['pattern']);
		$dot       = $koneksi->real_escape_string($_POST['dot']);
		$tahun     = $koneksi->real_escape_string($_POST['tahun']);
		$kerusakan = $koneksi->real_escape_string($_POST['kerusakan']);
		$tread     = $koneksi->real_escape_string($_POST['tread']);
		// $keputusan = $koneksi->real_escape_string($_POST['keputusan']);
		// if ($keputusan == "Tolak") {
		// 	$nominal = 0;
		// }else{
		// 	$nominal = $koneksi->real_escape_string($_POST['nominal']);
		// }
		// $crown     = $_POST['crown'];
		// $sidewall  = $_POST['sidewall'];
		// $bead      = $_POST['bead'];
		// $inner     = $_POST['inner'];
		// $outher    = $_POST['outher'];
		// $dot   		= $_POST['dot'];
		// $serial   	= $_POST['serial'];
		// $crown   	= $_POST['crown'];
		// $sidewall   = $_POST['sidewall'];
		// $bead   	= $_POST['bead'];
		// $inner   	= $_POST['inner'];
		// $outher   	= $_POST['outher'];
		// $keputusan  = $_POST['keputusan'];

		// echo "tgl $tgl <br>";
		// echo "no_claim $no_claim <br>";
		// echo "daerah $daerah <br>";
		// echo "dealer $dealer <br>";
		// echo "toko $toko <br>";
		// echo "brg $brg <br>";
		// echo "brand $brand <br>";
		// echo "dot $dot <br>";
		// echo "serial $serial <br>";
		// echo "crown $crown <br>";
		// echo "sidewall $sidewall <br>";
		// echo "bead $bead <br>";
		// echo "inner $inner <br>";
		// echo "outher $outher <br>";
		// echo "keputusan $keputusan <br>";

		$query = "INSERT INTO claim (no_claim,
									 pengaduan,
									 tgl, 
									 daerah,
									 dealer,
									 toko,
									 sales,
									 id_brg,
									 pattern,
									 dot,
									 tahun,
									 kerusakan,
									 tread,
									 keputusan,
									 nominal)
							  VALUES('$no_claim',
							  		 '$pengaduan',
							  		 '$tgl',
							  		 '$daerah', 
							  		 '$dealer',
							  		 '$toko',
							  		 '$sales',
							  		 '$id_brg',
							  		 '$pattern',
							  		 '$dot',
							  		 '$tahun',
							  		 '$kerusakan',
							  		 '$tread',
							  		 'Proses',
							  		 '0')";
		if ($koneksi->query($query) === TRUE) {
			$valid['success'] = true;
			$valid['messages'] = "<strong>Success! </strong>Data Berhasil Disimpan";
		}else{
			$valid['success']  = false;
			$valid['messages'] = "<strong>Error! </strong> Data Gagal Disimpan ".$koneksi->error;
		}

		$koneksi->close();
		echo json_encode($valid);
	}
?>