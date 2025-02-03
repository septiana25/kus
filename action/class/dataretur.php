<?php
/* class dataretur */
class DataRetur
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Fetch all data retur
     * @param string 
     * 
     */
    public function fetchAll()
    {
        $zero = 0;
        $stmt = $this->conn->prepare("SELECT id_retur, brg, rak, qty, sisa_qty AS sisa, user
                                        FROM tmp_retur
                                        LEFT JOIN barang USING(id_brg)
                                        LEFT JOIN rak USING(id_rak)
                                        WHERE sisa_qty > ?");
        $stmt->bind_param("i", $zero);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchReturById($id_retur)
    {
        $zero = 0;
        $stmt = $this->conn->prepare("SELECT id_retur, brg, rak, qty, sisa_qty AS sisa
                                        FROM tmp_retur
                                        LEFT JOIN barang USING(id_brg)
                                        LEFT JOIN rak USING(id_rak)
                                        WHERE id_retur = ? AND sisa_qty > ?");
        $stmt->bind_param("ii", $id_retur, $zero);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getEkspedisiByNopol($nopol)
    {
        $stmt = $this->conn->prepare("SELECT id_eks, supir, nopol, jenis 
                                        FROM ekspedisi
                                        WHERE nopol = ?");
        $stmt->bind_param("s", $nopol);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function insert($inputs)
    {

        $stmt = $this->conn->prepare("INSERT INTO ekspedisi (nopol, supir, jenis) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $inputs['nopol'], $inputs['supir'], $inputs['jenis']);
        return $stmt->execute();
    }

    public function insertItemRetur($inputs)
    {
        $stmt = $this->conn->prepare("INSERT INTO tmp_retur (id_brg, id_rak, qty, sisa_qty, user) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $inputs['barang'], $inputs['rak'], $inputs['qty'], $inputs['qty'], $inputs['user']);
        return $stmt->execute();
    }

    public function update($id_retur)
    {
        $zero = 0;
        $stmt = $this->conn->prepare("UPDATE tmp_retur SET sisa_qty = ? WHERE id_retur = ?");
        $stmt->bind_param("ii", $zero, $id_retur);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
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
}
