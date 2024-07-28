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
        $stmt = $this->conn->prepare("SELECT id_so, no_faktur, kode_toko, toko.toko AS toko, ekspedisi.nopol AS nopol, kdbrg, barang.brg AS brg, qty, sisa, `status`
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
}
