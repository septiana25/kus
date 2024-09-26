<?php
class Promosi
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
        $stmt = $this->conn->prepare("SELECT id_promo, divisi, item, jenis, saldo, note
                                        FROM promosi
                                        WHERE at_delete IS NULL
                                        ORDER BY jenis");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchPromosiByid($id_promo)
    {
        $stmt = $this->conn->prepare("SELECT id_promo, divisi, item, jenis, saldo, note
                                        FROM promosi
                                        WHERE id_promo = ? AND at_delete IS NULL");
        $stmt->bind_param("i", $id_promo);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPromosiByItem($item)
    {
        $stmt = $this->conn->prepare("SELECT id_promo, item, jenis, saldo
                                        FROM promosi
                                        WHERE item = ? AND at_delete IS NULL");
        $stmt->bind_param("s", $item);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function insert($inputs)
    {

        $stmt = $this->conn->prepare("INSERT INTO promosi (divisi, item, jenis, note) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $inputs['divisi'], $inputs['item'], $inputs['jenis'], $inputs['note']);
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
