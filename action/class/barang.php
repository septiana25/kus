<?php
class Barang
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function saveDetail($id_barang, $id_rak)
    {
        $stmt = $this->conn->prepare("INSERT INTO detail_brg (id_brg, id_rak) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_barang, $id_rak);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }

    public function fetchByItem($item, $limit = 20)
    {
        $stmt = $this->conn->prepare("
            SELECT id_brg, brg 
            FROM barang 
            WHERE kdbrg != 'LAMA' 
              AND brg LIKE ?
            ORDER BY 
              CASE 
                WHEN brg LIKE ? THEN 1
                WHEN brg LIKE ? THEN 2
                ELSE 3
              END,
              brg ASC
            LIMIT ?
        ");

        $searchStart = $item . '%';
        $searchAnywhere = '%' . $item . '%';
        $stmt->bind_param("sssi", $searchAnywhere, $searchStart, $searchAnywhere, $limit);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getByItem($item)
    {
        $stmt = $this->conn->prepare("SELECT id_brg FROM barang WHERE brg = ?");
        $stmt->bind_param("s", $item);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getByItemByRak($month, $year, $kdbrg, $rak)
    {
        $stmt = $this->conn->prepare("SELECT id, id_saldo FROM detail_brg
                            LEFT JOIN barang USING(id_brg)
                            LEFT JOIN rak USING(id_rak)
                            LEFT JOIN saldo USING(id)
                            WHERE MONTH(saldo.tgl) = ? AND YEAR(saldo.tgl)= ? AND kdbrg = ? AND rak = ?");
        $stmt->bind_param("iiss", $month, $year, $kdbrg, $rak);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getItemById($id, $idRak)
    {
        $stmt = $this->conn->prepare("SELECT id FROM detail_brg WHERE id_brg = ? AND id_rak= ?");
        $stmt->bind_param("ii", $id, $idRak);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getItemJoinDetail($id)
    {
        $stmt = $this->conn->prepare("SELECT id_brg, brg, id_rak, rak FROM barang JOIN detail_brg USING(id_brg) JOIN rak USING(id_rak) WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
