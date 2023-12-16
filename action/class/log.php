<?php
class Log
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function insertLog($nama, $aksi, $ket)
    {
        $stmt = $this->conn->prepare("INSERT INTO log (nama, aksi, ket) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $aksi, $ket);
        return $stmt->execute();
    }
}
