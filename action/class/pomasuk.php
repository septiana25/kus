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
        $stmt = $this->conn->prepare("INSERT INTO pomasuk (id_msk, id_barcodebrg, qty_po, qty_sisa, no_polisi, `status`, note, user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $idMsk, $idBarcode, $qty, $qty, $nopol, $status, $ket, $nama);
        return $stmt->execute();
    }

    public function saveDetailPO($idMsk, $idPOMsk, $idDetMsk, $idPoMskScanDetail)
    {
        $stmt = $this->conn->prepare("INSERT INTO detail_pomasuk (id_msk, id_pomsk, id_det_msk, id_masuk_det) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiii", $idMsk, $idPOMsk, $idDetMsk, $idPoMskScanDetail);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }

    public function fetchPoMasuk()
    {
        $stmt = $this->conn->prepare("SELECT id_pomsk, id_msk, suratJln, no_polisi, brg, qty_po AS qty, qty_sisa, `status` 
                                        FROM pomasuk
                                        LEFT JOIN masuk USING(id_msk)
                                        LEFT JOIN barcodebrg USING(id_barcodebrg)
                                        LEFT JOIN barang USING(id_brg)");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPoMasukById($id)
    {
        $stmt = $this->conn->prepare("SELECT id_pomsk, id_msk, suratJln
                                        FROM pomasuk
                                        LEFT JOIN masuk USING(id_msk)
                                        WHERE id_pomsk = ? AND `status` = ?");
        $status = "INPG";
        $stmt->bind_param("ss", $id, $status);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPoMasukDetailById($id)
    {
        $stmt = $this->conn->prepare("SELECT id_masuk_det 
                                        FROM detail_pomasuk
                                        WHERE id_msk = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
}
