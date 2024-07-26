<?php
class Toko
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Fetch all data toko
     * @param string 
     * 
     */
    public function fetchAll()
    {
        $stmt = $this->conn->prepare("SELECT id_toko, kode_toko, toko, alamat FROM toko ORDER BY kode_toko DESC");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function insert($inputs)
    {
        $stmt = $this->conn->prepare("INSERT INTO toko (kode_toko, toko, alamat) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $inputs['kode_toko'], $inputs['toko'], $inputs['alamat']);
        return $stmt->execute();
    }

    public function update($inputs)
    {
        $stmt = $this->conn->prepare("UPDATE toko SET kode_toko = ?, toko = ?, alamat = ? WHERE id_toko = ?");
        $stmt->bind_param("sssi", $inputs['kode_toko'], $inputs['toko'], $inputs['alamat'], $inputs['id_toko']);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function getTokoByKodetoko($kode_toko)
    {
        $stmt = $this->conn->prepare("SELECT  id_toko, kode_toko, toko, alamat FROM toko WHERE kode_toko = ?");
        $stmt->bind_param("s", $kode_toko);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getTokoById($id_toko)
    {
        $stmt = $this->conn->prepare("SELECT id_toko, kode_toko, toko, alamat FROM toko WHERE id_toko = ?");
        $stmt->bind_param("i", $id_toko);
        $stmt->execute();
        return $stmt->get_result();
    }
}
