<?php
class Ekspedisi
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Fetch all data ekspedisi
     * @param string 
     * 
     */
    public function fetchAll()
    {
        $stmt = $this->conn->prepare("SELECT id_eks, supir, nopol, jenis
                                        FROM ekspedisi
                                        WHERE at_delete IS NULL
                                        ORDER BY supir");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchEkspedisiByid($id_eks)
    {
        $stmt = $this->conn->prepare("SELECT id_eks, supir, nopol, jenis 
                                        FROM ekspedisi
                                        WHERE id_eks = ?");
        $stmt->bind_param("i", $id_eks);
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
