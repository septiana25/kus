<?php
class PoMasuk
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function fetchNopol($nopol)
    {
        $stmt = $this->conn->prepare("SELECT no_polisi FROM pomasuk WHERE no_polisi LIKE ? GROUP BY no_polisi LIMIT 10");
        $searchTerm = "%" . $nopol . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function insertPoMasuk($idMsk, $idBarcode, $qty, $nopol, $status, $ket, $nama)
    {
        $stmt = $this->conn->prepare("INSERT INTO pomasuk (id_msk, id_barcodebrg, qty_po, no_polisi, `status`, note, user) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $idMsk, $idBarcode, $qty, $nopol, $status, $ket, $nama);
        return $stmt->execute();
    }
}