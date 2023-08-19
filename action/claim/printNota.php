<style type="text/css">
	.control-label {
		text-align: left;
		width: 300px;
	}

	label{
		display: block;
		margin-bottom: -10px; 
		font-size: 14px;
		font-weight: normal;
		line-height: 20px;
	}

	strong{
		font-weight: normal;
	}

	.titik2 {
		padding-left: 130px;
		margin-top: -20px;
		font-weight: bold;
	}

	#nota th, td{
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

	.isi {
		padding-left: 82px;
		margin-top: -20px;
		font-weight: normal;
	}

	.mar{
		margin-top:-15px;
	}

	#mar{
		margin-top:-20px;
	}



</style>

<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/session.php';
require_once '../../function/fungsi_rupiah.php';
require_once '../../function/tgl_indo.php';

$idNota = $koneksi->real_escape_string($_POST['idNota']);
// $idNota = 17;

	$queryNota = "SELECT toko, total, keputusan
	FROM tblNota
	JOIN tblDetNota USING(idNota)
	LEFT JOIN claim USING(id_claim) WHERE idNota=$idNota GROUP BY toko";
	$resultNota = $koneksi->query($queryNota);
	$rowNota = $resultNota->fetch_array();	


	$queryPrint = "SELECT idNota, toko, brg, pattern, dot, tahun, no_claim, nominal, total, pengaduan, ket
	FROM tblNota
	JOIN tblDetNota USING(idNota)
	LEFT JOIN claim USING(id_claim)
	LEFT JOIN barang USING(id_brg) WHERE idNota=$idNota";
	$resultPrint = $koneksi->query($queryPrint);
	// $rowSatu = $resultPrint->fetch_array();

if ($rowNota[2] == 'Tolak') 
{

	$tabel ='
			<table rules="none" border="1" width="100%">
				<thead>
				<tr>
					<td>
						<center>
							<h2>CV.KHARISMA TIARA ABADI</h2>
							<p class="mar" id="mar">Kav.Industri Satria Raya No.9 Bandung 40224</p>
							<p class="mar">Phone : (022) 542.3331, (022) 541.3538 </p>
							<p style="text-decoration: underline; font-weight:bold;">TANDA TERIMA BERKAS CLAIM TOLAKAN</p>
							
						</center>
						<label class="control-label">
							<strong>Nama Toko</strong>
							<p class="titik2">: '.$rowNota[0].'</p>
						</label>

						<label class="control-label">
							<strong>Telah Terima Berupa</strong>
							<p class="titik2"> :</p>
						</label>

						<label class="oleh">
							<strong>Dicetak oleh</strong>
							<p class="isi">: '.$_SESSION['nama'].'</p>
						</label>

						<label class="tgl">
							<strong>Tanggal Cetak</strong>
							<p class="isi">: '.date("d-m-Y").'</p>
						</label>

						<table border="1" cellspacing="0" cellpadding="1" width="100%" id="nota">
							</thead>
								<tr>
									<th>NO</th>
									<th>TYPE</th>
									<th>NO.SERI</th>
									<th>NO.CLAIM</th>
									<th>KETERANGAN</th>
								</tr>';

								$no=1;
								while ($row = $resultPrint->fetch_array()) {
					  			$noSeri = $row['pattern'].'-'.$row['dot'].'-'.$row['tahun'];

							$tabel .='
								<tr>
								    <td style="text-align:center;">'.$no.'</td>
								    <td style="text-align:left;">'.$row[2].'</td>
								    <td style="text-align:center;">'.$noSeri.'</td>
								    <td style="text-align:center;">'.$row[9].'</td>
								    <td style="text-align:center;">'.$row[10].'</td>
								</tr>
								';
							  	$no++;
								}

							$tabel .='								
							<thead>
						</table>

						<table width="100%">
								<tr>
									<th width="33%">Diterima Oleh,</th>
									<th width="33%">Bag.Claim</th>
									<th width="33%">Bag.Gudang</th>
								</tr>
							<tbody>
								<tr>
									<td style="padding-top:35px; text-align:center;">Nama Jelas dan Tanda Tangan</td>
									<td style="padding-top:35px; text-align:center;">Agus. S</td>
									<td style="padding-top:35px; text-align:center;">Budi atau Nana</td>
								</tr>	
							</tbody>
						</table>

						<table rules="none" border="1" width="100%"">
							<tr>
								<td>Ditanda tangani/stempel dan dikembaikan ke PT.KHARISMA UTAMA SENTOSA/ CV.KHARISMA TIARA ABADI</td>
							</tr>
						</table>
						<br />
					</td>
				</tr>

				</thead>
			</table>
	';
	 
}

else{

	$tabel ='
			<table rules="none" border="1" width="100%">
				<thead>
				<tr>
					<td>
						<center>
							<h2>CV.KHARISMA TIARA ABADI</h2>
							<h2 style="margin-top: -18px;">Penggantian Claim Juli 2017</h2>
							<p style="text-decoration: underline; font-weight:bold;">TANDA TERIMA</p>
							
						</center>
						<label class="control-label">
							<strong>Nama Toko</strong>
							<p class="titik2">: '.$rowNota[0].'</p>
						</label>

						<label class="control-label">
							<strong>Nominal</strong>
							<p class="titik2">: '.format_rupiah($rowNota[1]).'</p>
						</label>

						<label class="oleh">
							<strong>Dicetak oleh</strong>
							<p class="isi">: '.$_SESSION['nama'].'</p>
						</label>

						<label class="tgl">
							<strong>Tanggal Cetak</strong>
							<p class="isi">: '.date("d-m-Y").'</p>
						</label>

						<table border="1" cellspacing="0" cellpadding="1" width="100%" id="nota">
							</thead>
								<tr>
									<th>NO</th>
									<th>TYPE</th>
									<th>NO.SERI</th>
									<th>NO.PORTAL</th>
									<th>JML (RP)</th>
								</tr>';

								$no=1;
								while ($row = $resultPrint->fetch_array()) {
					  			$noSeri = $row['pattern'].'-'.$row['dot'].'-'.$row['tahun'];

							$tabel .='
								<tr>
								    <td style="text-align:center;">'.$no.'</td>
								    <td style="text-align:left;">'.$row[2].'</td>
								    <td style="text-align:center;">'.$noSeri.'</td>
								    <td style="text-align:center;">'.$row[6].'</td>
								    <td style="text-align:right;">'.format_rupiah($row[7]).'</td>
								</tr>
								';
							  	$no++;
								}

							$tabel .='	
								<tr>
									<td colspan="4" style="text-align:center; font-weight:bold">TOTAL</td>
									<td style="text-align:right; font-weight:bold;">'.format_rupiah($rowNota[1]).'</td>
								</tr>							
							<thead>
						</table>

						<p style="margin-top: auto;">Keterangan</p>
						<p style="margin-top: -12; padding-left: 28px;">- Ditanda tangani / stempel toko & dikembalikan ke CV.Karisma Tiara Abadi</p>

						<table width="100%">
								<tr>
									<th width="50%">Distributot Goodyear</th>
									<th width="50%">Toko</th>
								</tr>
							<tbody>
								<tr>
									<td style="padding-top:35px; text-align:center;">CV.Karisma Tiara Abadi</td>
									<td style="padding-top:35px; text-align:center;">Tanda Tangan & Stempel</td>
								</tr>	
							</tbody>
						</table>

					</td>
				</tr>

				</thead>
			</table>
	';

}// END Keputusan Ganti dan Ganti SC



	$koneksi->close();
	echo $tabel;
?>