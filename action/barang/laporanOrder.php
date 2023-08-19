<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';


if ($_GET) {
//date('Y-m-d', strtotime('-6 days', strtotime( variabel_tgl_awal ))); //kurang tanggal sebanyak 6 hari

//array bulan
//$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");


	$id_order = $_GET['id'];

	$cekOrder = $koneksi->query("SELECT toko, UPPER(sales) AS sales, tglOrder, top, ketOrder FROM tblOrder WHERE id_order = $id_order");
	$rowOrder  =  $cekOrder->fetch_assoc();
	$tglOrder =  $rowOrder['tglOrder'];
	$sales       =  $rowOrder['sales'];
	$toko       =  $rowOrder['toko'];

/*	$rest = $koneksi->query($query);
	$fetch = $rest->fetch_all(MYSQL_ASSOC);
//echo "<pre>". print_r($fetch); die;

    foreach($fetch as $c => $key) {
        //$sort_faktur[] = $key['suratJln'];
        $sort_tgl[] = $key['tgl_msk'];
        $sort_msk[] = $key['msk'];

    }*/



if ($cekOrder->num_rows > 0) {

echo '
		<style>
		body {
			font-family:"segoe ui", "open sans", tahoma, arial;
		}
		table {
			border-collapse: collapse;
			font-size: 13px;
		}
		.tengah {
			text-align: center;
		}
		.kanan {
			text-align: right;
		}
		.table tr:nth-child(odd) th {
			background-color: #fbfbfb;
			border-bottom: 1px solid #585656;
			border-top: 1px solid #5a5353;
		}
		.table th {
			text-transform: uppercase;
		}
		.padding {
			padding: 0 5px 0 5px;
		}
		.padding-total{
			padding: 5px 5px 5px 0;
		}
		.batas-atas {
			padding-top : 5px;
		}

		.batas-atas1 {
			padding-top : 50px;
		}
		.table2 {
			text-align: left;
		}
		.padding-head {
			padding: 2px 5px 0 0;
		}
		.atas-padding {
			padding-bottom : 62px;
		}

		label{
			display: block;
			margin-bottom: -10px; 
			font-size: 13px;
			font-weight: normal;
			line-height: 17px;
		}

		.titik2 {
			padding-left: 130px;
			margin-top: -20px;
			font-weight: bold;
		}
		.padingbottom{
			padding-bottom:5px;
		}


		</style>
';

echo '<br/>
		<table border="1" width="50%" class="table">
			<thead>
				<tr>
					<td>
					
		<label class="control-label batas-atas">
			<strong>TGL ORDER</strong>
			<p class="titik2">: '.$tglOrder.'</p>
		</label>
		<label class="control-label">
			<strong>SALES</strong>
			<p class="titik2">: '.$sales.'</p>
		</label>
		<label class="control-label">
			<strong>TOKO</strong>
			<p class="titik2">: '.$toko.'</p>
		</label>
		<label class="control-label">
			<strong>TOP</strong>
			<p class="titik2">: '.$rowOrder['top'].'</p>
		</label>
		<label class="control-label padingbottom">
			<strong>KET</strong>
			<p class="titik2">: '.$rowOrder['ketOrder'].'</p>
		</label>

		<table border="1" width="100%" class="table">
			<thead>
				<tr>
                  <th >No</th>
                  <th >Nama Barang</th>
                  <th >QTY</th>
                  <th >DISC %</th>
                  <th >KET</th>
				</tr>
			</thead>
			<tbody>';
				$no = 1;
					$queryDelOrder = $koneksi->query("SELECT id_order, nama_brg, qty, CONCAT('Rp.', IFNULL(harga, '-'), ket_Det) AS ket FROM detail_order WHERE id_order =  $id_order");
					while ($row = $queryDelOrder->fetch_assoc()) 
					{
						echo '
						<tr>
							<td width="3%">'.$no.'</td>
							<td>'.$row['nama_brg'].'</td>
							<td width="7%" class="tengah">'.$row['qty'].'</td>
							<td width="8%" class="tengah">'.$row['diskon'].'</td>
							<td width="30%">  '.$row['ket'].'</td>
						</tr>';
						$no++;
					}
					
	
//$sAwal =  $val['s_awal'] + $val['tmbh_saldo'];
echo '

		</tbody>		
		</table>
					</td>
				</tr>
				
				<table width="50%">
						<tr>
							<th width="25%">Diajuakan</th>
							<th width="25%">Diterima</th>
							<th width="25%">Menyetujui</th>
							<th width="25%">Mengetahui</th>
						</tr>
					<tbody>
						<tr>
							<td style="padding-top:35px; text-align:center;">'.$sales.'</td>
							<td style="padding-top:35px; text-align:center;">.......................</td>
							<td style="padding-top:35px; text-align:center;">.......................</td>
							<td style="padding-top:35px; text-align:center;">.......................</td>
						</tr>	
					</tbody>
				</table>

			</thead>

		</table>

		';


}
else
{
	echo "Laporan Yang Anda Minta Tidak Anda";
}

$koneksi->close();

}

?>