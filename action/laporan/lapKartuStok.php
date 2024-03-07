<?php
require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';


if ($_POST) {
	//date('Y-m-d', strtotime('-6 days', strtotime( variabel_tgl_awal ))); //kurang tanggal sebanyak 6 hari

	//array bulan
	$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");


	$id_brgKartu = $koneksi->real_escape_string($_POST['id_brgKartu']);
	$bulanKartu  = $koneksi->real_escape_string($_POST['bulanKartu']);
	$tahunKartu  = $koneksi->real_escape_string($_POST['tahunKartu']);

	$cekSaldo = $koneksi->query("SELECT brg, SUM(saldo_awal) AS saldo_awal FROM saldo
									JOIN detail_brg USING(id)
									JOIN barang USING(id_brg)
								WHERE id_brg = $id_brgKartu AND MONTH(tgl) = $bulanKartu AND YEAR(tgl)=$tahunKartu ");
	$rowSaldo  =  $cekSaldo->fetch_assoc();
	$SaldoAwal =  $rowSaldo['saldo_awal'];
	$brg       =  $rowSaldo['brg'];


	$query = "SELECT suratJln, msk.tgl AS tgl_msk, NULL AS toko, jml_msk AS msk, NULL AS klr, rak.rak, tahunprod FROM detail_masuk
				LEFT JOIN tahunprod_masuk USING(id_det_msk)
				LEFT JOIN masuk AS msk USING(id_msk)
				LEFT JOIN detail_brg USING(id)
				LEFT JOIN rak USING(id_rak)
				LEFT JOIN barang USING(id_brg)
			WHERE MONTH(msk.tgl) = $bulanKartu AND YEAR(msk.tgl)=$tahunKartu AND id_brg =$id_brgKartu AND retur !='3'

			UNION ALL

			SELECT no_faktur, klr.tgl AS tgl_klr, toko, NULL, jml_klr AS klr, rak.rak, tahunprod FROM detail_keluar
				LEFT JOIN tahunprod_keluar USING(id_det_klr)
				LEFT JOIN keluar AS klr USING(id_klr)
				LEFT JOIN detail_brg USING(id)
				LEFT JOIN barang USING(id_brg)
				LEFT JOIN rak USING(id_rak)
				LEFT JOIN toko USING(id_toko)
			WHERE MONTH(klr.tgl) = $bulanKartu AND YEAR(klr.tgl)=$tahunKartu AND id_brg =$id_brgKartu";

	$rest = $koneksi->query($query);
	$fetch = $rest->fetch_all(MYSQLI_ASSOC);
	//echo "<pre>". print_r($fetch); die;

	foreach ($fetch as $c => $key) {
		//$sort_faktur[] = $key['suratJln'];
		$sort_tgl[] = $key['tgl_msk'];
		$sort_msk[] = $key['msk'];
	}



	if ($rest->num_rows > 0) {

		echo '
		<style>
		body {
			font-family:"segoe ui", "open sans", tahoma, arial;
		}
		table {
			border-collapse: collapse;
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

		</style>
';

		echo '<br/>
		<table width="100%">
			<thead>
				<tr>
					<th style="color:red;">LAPORAN AKTIVITAS UNIT KELUAR - MASUK BARANG (RINCI)</th>
				</tr>
			</thead>
		</table>
		<table class="table2">
			<thead >
				<tr>
				  <th class="tengah">Periode: ' . $BulanIndo[(int)$bulanKartu - 1] . ' ' . $tahunKartu . '</th>
				</tr>
			</thead>
		</table>

		<table border="1" width="100%" class="table">
			<thead>
				<tr>
					<th colspan="7">' . $brg . '</th>
				</tr>
				<tr>
                  <th >No</th>
                  <th >No Faktur</th>
                  <th >TGL Faktur</th>
                  <th >Pelanggan</th>
                  <th >Rak</th>
                  <th >Tahun</th>
                  <th >Masuk</th>
                  <th >Keluar</th>
                  <th >Saldo Akhir</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<th colspan="2" class="padding">SALDO AWAL</th>
					<th colspan="4" class="kanan padding">' . format_rupiah($SaldoAwal) . '</th>
				</tr>';


		$no         = 1;
		$awal       = $SaldoAwal;
		$saldo      = "";
		$totalMSK   = "";
		$totalKLR   = "";

		array_multisort($sort_tgl, SORT_ASC, $sort_msk, SORT_DESC, $fetch);

		foreach ($fetch as $key => $val) {
			if (empty($val['msk'])) {
				$saldo = $awal - $val['klr'];
				$msk = $val['msk'];
			} else {
				$saldo = $awal + $val['msk'];
				$msk = format_rupiah($val['msk']);
			}

			if (empty($val['klr'])) {

				$klr = $val['klr'];
			} else {

				$klr = format_rupiah($val['klr']);
			}


			echo '<tr><td class="padding"> ' . $no . '</td>
				  <td class="padding" >' . $val['suratJln'] . '</td>
				  <td class="kanan padding"> ' . TanggalIndo($val['tgl_msk']) . '</td>
				  <td class="padding">' . $val['toko'] . '</td>
				  <td class="padding">' . $val['rak'] . '</td>
				  <td class="kanan padding">' . $val['tahunprod'] . '</td>
				  <td class="kanan padding">' . $msk . '</td>
				  <td class="kanan padding">' . $klr . '</td>
				  <td class="kanan padding">' . format_rupiah($saldo) . '</td>
				</tr>';
			$awal = $saldo;

			$no++;
			$totalMSK   += $val['msk'];
			$totalKLR   += $val['klr'];
			//$totalSaldo +=$saldo;

		}
		//$sAwal =  $val['s_awal'] + $val['tmbh_saldo'];
		echo '
          <tfoot>
            <tr>
              <th class="kanan padding-total" colspan="6"></th>
              <th class="kanan padding-total">' . format_rupiah($totalMSK) . '</th>
              <th class="kanan padding-total">' . format_rupiah($totalKLR) . '</th>
              <th class="kanan padding-total">' . format_rupiah($saldo) . '</th>
            </tr>

          </tfoot>
		</tbody>		
		</table>';
	} else {
		echo "Laporan Yang Anda Minta Tidak Anda";
	}

	$koneksi->close();
}
