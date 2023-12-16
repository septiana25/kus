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

    public function fetchByBarcode($barcode)
    {
        $stmt = $this->conn->prepare("SELECT brg FROM barcodebrg JOIN barang USING(id_brg) WHERE barcode LIKE ? LIMIT 10");
        $searchTerm = "%" . $barcode . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }
}
