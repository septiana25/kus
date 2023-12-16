<?php
class Masuk
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getNoPO($noPO, $tgl)
    {
        $stmt = $this->conn->prepare("SELECT id_msk FROM masuk WHERE tgl = ? AND suratJln = ?");
        $stmt->bind_param("ss", $tgl, $noPO);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function insertMasuk($noPO, $tgl, $nama)
    {
        $stmt = $this->conn->prepare("INSERT INTO masuk (suratJln, tgl, pembuat) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $noPO, $tgl, $nama);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }
}
