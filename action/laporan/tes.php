<?php

require_once '../../function/koneksi.php';
require_once '../../function/setjam.php';
require_once '../../function/tgl_indo.php';
require_once '../../function/fungsi_rupiah.php';

	$query = "SELECT suratJln, msk.tgl AS tgl_msk, NULL AS toko, SUM(jml_msk) AS msk, NULL AS klr FROM detail_masuk
				JOIN masuk AS msk USING(id_msk)
				JOIN detail_brg USING(id)
				JOIN barang USING(id_brg)
			WHERE MONTH(msk.tgl) = 11 AND YEAR(msk.tgl)=2017 AND id_brg =543
			GROUP BY suratJln

			UNION ALL

			SELECT no_faktur, klr.tgl AS tgl_klr, toko, NULL, SUM(jml_klr) AS klr FROM detail_keluar
				JOIN keluar AS klr USING(id_klr)
				JOIN detail_brg USING(id)
				JOIN barang USING(id_brg)
			WHERE MONTH(klr.tgl) = 11 AND YEAR(klr.tgl)=2017 AND id_brg =543
			GROUP BY no_faktur ";

	$rest = $koneksi->query($query);
	$fetch = $rest->fetch_all(MYSQL_ASSOC);
        
        //echo '<pre>'.print_r($fetch, true).'</pre>';
        
    foreach($fetch as $c=>$key) {
        //$sort_faktur[] = $key['suratJln'];
        $sort_tgl[] = $key['tgl_msk'];
        $sort_msk[] = $key['msk'];

    }

    array_multisort($sort_tgl, SORT_ASC, $fetch);

    foreach ($fetch as $key => $val) {
    	echo $val['suratJln'].'<br/>';
    	echo $val['tgl_msk'].'<br/>';
    }

    //echo '<pre>'.print_r($fetch, true).'</pre>';
    
?> 