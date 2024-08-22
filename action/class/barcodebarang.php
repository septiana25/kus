<?php
class BarocdeBarang
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function fetchAll()
    {
        $stmt = $this->conn->prepare("SELECT id_brg, barcode_brg, brg, satuan, qty 
                                        FROM barcodebrg JOIN barang USING(id_brg)
                                        WHERE at_delete IS NULL");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchByItem($item)
    {
        $stmt = $this->conn->prepare("SELECT id_brg, brg 
                                        FROM barcodebrg JOIN barang USING(id_brg) 
                                        WHERE brg LIKE ? LIMIT 10");
        $searchTerm = "%" . $item . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT id_brg, barcode_brg, brg, satuan, qty 
                                        FROM barcodebrg JOIN barang USING(id_brg) 
                                        WHERE id_brg = ? AND at_delete IS NULL");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getByItem($item)
    {
        $stmt = $this->conn->prepare("SELECT id_brg, id_barcodebrg FROM barcodebrg JOIN barang USING(id_brg) 
                                        WHERE brg = ? AND at_delete IS NULL");
        $stmt->bind_param("s", $item);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getByBarcode($barcode)
    {
        $stmt = $this->conn->prepare("SELECT id_brg, brg 
                                        FROM barcodebrg JOIN barang USING(id_brg) 
                                        WHERE barcode_brg = ? AND at_delete IS NULL");
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

    public function updateBarcode($inputs)
    {
        $currentDateTime = date('Y-m-d H:i:s');
        $stmt = $this->conn->prepare("UPDATE barcodebrg SET barcode_brg = ?, satuan = ?, qty = ?, user = ?, at_update = ? WHERE id_brg = ?");
        $stmt->bind_param("sssssi", $inputs['barcode_brg'], $inputs['satuan'], $inputs['qty'], $inputs['user'], $currentDateTime, $inputs['id_brg']);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function delete($id_brg, $atDelete)
    {
        $stmt = $this->conn->prepare("UPDATE barcodebrg SET at_delete = ? WHERE id_brg = ?");
        $stmt->bind_param("si", $atDelete, $id_brg);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }
}
