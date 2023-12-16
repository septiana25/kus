<?php
class Barang
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
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

    public function getItemById($id)
    {
        $stmt = $this->conn->prepare("SELECT id_brg FROM barang WHERE id_brg = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
