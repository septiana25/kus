<?php
class BarocdeBarang
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function fetchByItem($item)
    {
        $stmt = $this->conn->prepare("SELECT id_brg, brg FROM barcodebrg JOIN barang USING(id_brg) WHERE brg LIKE ? LIMIT 10");
        $searchTerm = "%" . $item . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT id_brg FROM barcodebrg WHERE id_brg = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getByItem($item)
    {
        $stmt = $this->conn->prepare("SELECT id_brg, id_barcodebrg FROM barcodebrg JOIN barang USING(id_brg) WHERE brg = ?");
        $stmt->bind_param("s", $item);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getByBarcode($barcode)
    {
        $stmt = $this->conn->prepare("SELECT id_brg, brg FROM barcodebrg JOIN barang USING(id_brg) WHERE barcode_brg = ?");
        $stmt->bind_param("s", $barcode);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function insertBarcode($idBrg, $barcode, $qty, $satuan, $nama)
    {
        $stmt = $this->conn->prepare("INSERT INTO barcodebrg (id_brg, barcode_brg, qty, satuan, user) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("sssss", $idBrg, $barcode, $qty, $satuan, $nama);
        return $stmt->execute();
    }
}
