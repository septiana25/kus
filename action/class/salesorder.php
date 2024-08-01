<?php
class Salesorder
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Fetch all data tmp_salesorder
     * @param string 
     * 
     */
    public function getDataSalesOrderUnprocessed()
    {
        $stmt = $this->conn->prepare("SELECT id_so, no_faktur, kode_toko, toko.toko AS toko, ekspedisi.nopol AS nopol, supir, kdbrg, barang.brg AS brg, qty, sisa, `status`
                                        FROM tmp_salesorder
                                        LEFT JOIN toko USING(kode_toko)
                                        LEFT JOIN barang USING(kdbrg)
                                        LEFT JOIN ekspedisi USING(nopol)
                                        WHERE tmp_salesorder.at_update IS NULL AND tmp_salesorder.at_delete IS NULL
                                        ORDER BY kode_toko");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDataSalesOrderByStatus($status)
    {
        $stmt = $this->conn->prepare("SELECT id_so, no_faktur, kode_toko, toko.toko AS toko, ekspedisi.nopol AS nopol, kdbrg, barang.brg AS brg, sisa AS qty, `status`
                                        FROM tmp_salesorder
                                        LEFT JOIN toko USING(kode_toko)
                                        LEFT JOIN barang USING(kdbrg)
                                        LEFT JOIN ekspedisi USING(nopol)
                                        WHERE tmp_salesorder.at_update IS NULL AND tmp_salesorder.at_delete IS NULL AND tmp_salesorder.status = ?");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchSelesOrderByid($id_so)
    {
        $stmt = $this->conn->prepare("SELECT id_so, no_faktur, kode_toko, nopol, kdbrg, barang.brg AS brg, qty 
                                        FROM tmp_salesorder
                                        LEFT JOIN barang USING(kdbrg)
                                        WHERE id_so = ?");
        $stmt->bind_param("i", $id_so);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function saveSalesOrder($inputs)
    {
        $stmt = $this->conn->prepare("INSERT INTO tmp_salesorder (no_faktur, kode_toko, nopol, kdbrg, qty, sisa) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii", $inputs['no_faktur'], $inputs['kode_toko'], $inputs['nopol'], $inputs['kdbrg'], $inputs['qty'], $inputs['qty']);
        $stmt->execute();
        return ['success' => true, 'id' => $this->conn->insert_id];
    }

    public function update($inputs)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_salesorder SET nopol = ?, kdbrg = ?, kode_toko = ?, qty = ? WHERE id_so = ?");
        $stmt->bind_param("sssii", $inputs['nopol'], $inputs['kdbrg'], $inputs['kode_toko'], $inputs['qty'], $inputs['id_so']);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function updateStatusSalesOrder($id_so, $status)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_salesorder SET `status` = ? WHERE id_so = ?");
        $stmt->bind_param("si", $status, $id_so);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function updateDateUpdateSalesOrder($id_so, $atUpdate)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_salesorder SET `at_update` = ? WHERE id_so = ?");
        $stmt->bind_param("si", $atUpdate, $id_so);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }


    public function updateSisaSalesOrder($id_so, $sisa)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_salesorder SET `sisa` = ? WHERE id_so = ?");
        $stmt->bind_param("ii", $sisa, $id_so);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function delete($id_so, $atDelete)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_salesorder SET at_delete = ? WHERE id_so = ?");
        $stmt->bind_param("si", $atDelete, $id_so);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function insertProssesSalesOrder($inputs)
    {
        $stmt = $this->conn->prepare("INSERT INTO tmp_prossessso (id_so, id_detailsaldo, qty_pro) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $inputs['id_so'], $inputs['id_detailsaldo'], $inputs['qty_pro']);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }

    public function updateNotaProssesSalesOrder($id_pro, $nota)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_salesorder SET nota = ? WHERE id_pro = ?");
        $stmt->bind_param("is", $nota, $id_pro);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function getDataProsessSalesOrder()
    {
        $stmt = $this->conn->prepare("SELECT nopol, supir, no_nota, tmp_prossessso.at_create AS tgl, COUNT(DISTINCT tmp_salesorder.no_faktur) AS faktur
                                        FROM tmp_prossessso
                                        LEFT JOIN tmp_salesorder USING(id_so)
                                        LEFT JOIN ekspedisi USING(nopol)
                                        WHERE no_nota IS NULL
                                        GROUP BY nopol");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDataDetailProsessSalesOrder($nopol)
    {
        $stmt = $this->conn->prepare("SELECT id_pro, detail_brg.id, id_detailsaldo, jenis, nopol, supir, id_toko, toko.toko AS toko, barang.kdbrg, barang.brg, rak, tahunprod, SUM(qty_pro) AS qty_pro
                                        FROM tmp_prossessso
                                        LEFT JOIN tmp_salesorder USING(id_so)
                                        LEFT JOIN ekspedisi USING(nopol)
                                        LEFT JOIN toko USING(kode_toko)
                                        LEFT JOIN detail_saldo USING(id_detailsaldo) 
                                        LEFT JOIN detail_brg USING(id)
                                        LEFT JOIN barang USING(id_brg)
                                        LEFT JOIN rak USING(id_rak)
                                        WHERE no_nota IS NULL AND nopol = ?
                                        GROUP BY kdbrg, rak");
        $stmt->bind_param("s", $nopol);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDataDetailProsessSOForKeluar($nopol)
    {
        $stmt = $this->conn->prepare("SELECT id_pro, detail_brg.id, id_detailsaldo, jenis, nopol, supir, id_toko, toko.toko AS toko, no_faktur, barang.kdbrg, barang.brg, rak, tahunprod, qty_pro, tmp_salesorder.note
                                        FROM tmp_prossessso
                                        LEFT JOIN tmp_salesorder USING(id_so)
                                        LEFT JOIN ekspedisi USING(nopol)
                                        LEFT JOIN toko USING(kode_toko)
                                        LEFT JOIN detail_saldo USING(id_detailsaldo) 
                                        LEFT JOIN detail_brg USING(id)
                                        LEFT JOIN barang USING(id_brg)
                                        LEFT JOIN rak USING(id_rak)
                                        WHERE no_nota IS NULL AND nopol = ?");
        $stmt->bind_param("s", $nopol);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDataDetailProsessSalesOrderByIdPro($id_pro)
    {
        $stmt = $this->conn->prepare("SELECT id_pro, id, id_detailsaldo, barang.brg, tahunprod, qty_pro
                                        FROM tmp_prossessso
                                        LEFT JOIN tmp_salesorder USING(id_so)
                                        LEFT JOIN detail_saldo USING(id_detailsaldo) 
                                        LEFT JOIN detail_brg USING(id)
                                        LEFT JOIN barang USING(id_brg)
                                        WHERE id_pro = ?");
        $stmt->bind_param("i", $id_pro);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateQtyProssesSalesOrder($id_pro, $qty_pro)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_prossessso SET qty_pro = ? WHERE id_pro = ?");
        $stmt->bind_param("ii", $qty_pro, $id_pro);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Gagal Mengubah Qty"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function updateNoNotaProssesSalesOrder($id_pro, $no_nota)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_prossessso SET no_nota = ? WHERE id_pro = ?");
        $stmt->bind_param("si", $no_nota, $id_pro);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Gagal Mengubah No Nota"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }
}
