<?php

  $sql = "SELECT s.brg, s_awal, IFNULL(total_masuk, NULL) AS b_masuk,
  tgl_1,  tgl_2,  tgl_3,  tgl_4,  tgl_5,  tgl_6,  tgl_7,  tgl_8,  tgl_9,  tgl_10, tgl_11, tgl_12, tgl_13, tgl_14,
  tgl_15, tgl_16, tgl_17, tgl_18, tgl_19, tgl_20, tgl_21, tgl_22, tgl_23, tgl_24, tgl_25, tgl_26, tgl_27, tgl_28, 
  tgl_29, tgl_30, adjusmen, IFNULL(total_keluar, NULL) AS total_keluar, s_akhir, kat
FROM(
  SELECT id_rak, rak, id_brg, id, brg, SUM(saldo_awal) AS s_awal, SUM(saldo_akhir) AS s_akhir, kat
  FROM detail_brg
  JOIN saldo USING(id)
  JOIN barang USING(id_brg)
  JOIN rak USING(id_rak)
  JOIN kat USING(id_kat)
  WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun
  GROUP BY id_brg
)s 
LEFT JOIN(
  SELECT id_rak, id, tgl, id_brg, SUM(jml_klr) AS total_keluar,
    SUM( IF( DAY(tgl)=1 AND status_klr = '0', jml_klr, NULL)) AS tgl_1,
    SUM( IF( DAY(tgl)=2 AND status_klr = '0', jml_klr, NULL)) AS tgl_2,
    SUM( IF( DAY(tgl)=3 AND status_klr = '0', jml_klr, NULL)) AS tgl_3,
    SUM( IF( DAY(tgl)=4 AND status_klr = '0', jml_klr, NULL)) AS tgl_4,
    SUM( IF( DAY(tgl)=5 AND status_klr = '0', jml_klr, NULL)) AS tgl_5,
    SUM( IF( DAY(tgl)=6 AND status_klr = '0', jml_klr, NULL)) AS tgl_6,
    SUM( IF( DAY(tgl)=7 AND status_klr = '0', jml_klr, NULL)) AS tgl_7,
    SUM( IF( DAY(tgl)=8 AND status_klr = '0', jml_klr, NULL)) AS tgl_8,
    SUM( IF( DAY(tgl)=9 AND status_klr = '0', jml_klr, NULL)) AS tgl_9,
    SUM( IF( DAY(tgl)=10 AND status_klr = '0', jml_klr, NULL)) AS tgl_10,
    SUM( IF( DAY(tgl)=11 AND status_klr = '0', jml_klr, NULL)) AS tgl_11,
    SUM( IF( DAY(tgl)=12 AND status_klr = '0', jml_klr, NULL)) AS tgl_12,
    SUM( IF( DAY(tgl)=13 AND status_klr = '0', jml_klr, NULL)) AS tgl_13,
    SUM( IF( DAY(tgl)=14 AND status_klr = '0', jml_klr, NULL)) AS tgl_14,
    SUM( IF( DAY(tgl)=15 AND status_klr = '0', jml_klr, NULL)) AS tgl_15,
    SUM( IF( DAY(tgl)=16 AND status_klr = '0', jml_klr, NULL)) AS tgl_16,
    SUM( IF( DAY(tgl)=17 AND status_klr = '0', jml_klr, NULL)) AS tgl_17,
    SUM( IF( DAY(tgl)=18 AND status_klr = '0', jml_klr, NULL)) AS tgl_18,
    SUM( IF( DAY(tgl)=19 AND status_klr = '0', jml_klr, NULL)) AS tgl_19,
    SUM( IF( DAY(tgl)=20 AND status_klr = '0', jml_klr, NULL)) AS tgl_20,
    SUM( IF( DAY(tgl)=21 AND status_klr = '0', jml_klr, NULL)) AS tgl_21,
    SUM( IF( DAY(tgl)=22 AND status_klr = '0', jml_klr, NULL)) AS tgl_22,
    SUM( IF( DAY(tgl)=23 AND status_klr = '0', jml_klr, NULL)) AS tgl_23,
    SUM( IF( DAY(tgl)=24 AND status_klr = '0', jml_klr, NULL)) AS tgl_24,
    SUM( IF( DAY(tgl)=25 AND status_klr = '0', jml_klr, NULL)) AS tgl_25,
    SUM( IF( DAY(tgl)=26 AND status_klr = '0', jml_klr, NULL)) AS tgl_26,
    SUM( IF( DAY(tgl)=27 AND status_klr = '0', jml_klr, NULL)) AS tgl_27,
    SUM( IF( DAY(tgl)=28 AND status_klr = '0', jml_klr, NULL)) AS tgl_28,
    SUM( IF( DAY(tgl)=29 AND status_klr = '0', jml_klr, NULL)) AS tgl_29,
    SUM( IF( DAY(tgl)=30 AND status_klr = '0', jml_klr, NULL)) AS tgl_30,
    SUM( IF(status_klr = '1', jml_klr, NULL)) AS adjusmen
  FROM detail_keluar
  LEFT JOIN keluar USING (id_klr)
  LEFT JOIN detail_brg USING(id)
  LEFT JOIN barang USING(id_brg)
  LEFT JOIN rak USING(id_rak)
  WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun
  GROUP BY id_brg
)k ON k.id_brg=s.id_brg
LEFT JOIN(
  SELECT id_rak, id, tgl, id_brg, SUM(jml_msk) AS total_masuk
  FROM detail_brg
  LEFT JOIN barang USING(id_brg)
  LEFT JOIN detail_masuk USING(id)
  JOIN masuk ON detail_masuk.id_msk = masuk.id_msk
  LEFT JOIN rak USING(id_rak)
  WHERE MONTH(tgl)=$bulan AND YEAR(tgl)=$tahun AND retur !='3'
  GROUP BY id_brg
)m ON s.id_brg=m.id_brg";
?>