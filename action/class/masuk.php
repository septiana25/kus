<?php
class Masuk
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function save($tgl, $noPO, $nama)
    {
        $stmt = $this->conn->prepare("INSERT INTO masuk (tgl, suratJln, pembuat) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $tgl, $noPO, $nama);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }

    public function saveDetail($idMsk, $id, $jam, $jmlMsk, $ket)
    {
        $stmt = $this->conn->prepare("INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $idMsk, $id, $jam, $jmlMsk, $ket);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }

    public function saveTahunProd($id_det_msk, $tahunprod)
    {
        $stmt = $this->conn->prepare("INSERT INTO tahunprod_masuk (id_det_msk, tahunprod) VALUES (?, ?)");
        $stmt->bind_param("is", $id_det_msk, $tahunprod);
        $success = $stmt->execute();
        return ['success' => $success];
    }

    public function getNoPOByDate($noPO, $tgl)
    {
        $stmt = $this->conn->prepare("SELECT id_msk FROM masuk WHERE tgl = ? AND suratJln = ?");
        $stmt->bind_param("ss", $tgl, $noPO);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getNoPO($noPO)
    {
        $stmt = $this->conn->prepare("SELECT id_msk FROM masuk WHERE suratJln = ?");
        $stmt->bind_param("s", $noPO);
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
