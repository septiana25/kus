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

    public function fetchByItem($item)
    {
        $stmt = $this->conn->prepare("SELECT id_brg, brg FROM barang WHERE brg LIKE ? LIMIT 10");
        $searchTerm = "%" . $item . "%";
        $stmt->bind_param("s", $searchTerm);
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

    public function getItemById($id, $idRak)
    {
        $stmt = $this->conn->prepare("SELECT id FROM detail_brg WHERE id_brg = ? AND id_rak= ?");
        $stmt->bind_param("ii", $id, $idRak);
        $stmt->execute();
        return $stmt->get_result();
    }
}
