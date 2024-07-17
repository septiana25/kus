<?php
class Masuk
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function save($tgl, $noPO, $nama, $retur = 0)
    {
        $stmt = $this->conn->prepare("INSERT INTO masuk (tgl, suratJln, pembuat, retur) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $tgl, $noPO, $nama, $retur);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }

    public function saveDetail($idMsk, $id, $jam, $jmlMsk, $ket = NULL, $status_msk = '0', $rak = NULL)
    {

        $stmt = $this->conn->prepare("INSERT INTO detail_masuk (id_msk, id, jam, jml_msk, ket, rak, status_msk) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $idMsk, $id, $jam, $jmlMsk, $ket, $rak, $status_msk);
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

    public function deleteDetailMasukTahunProd($id_det_msk)
    {
        $stmt = $this->conn->prepare("DELETE FROM tahunprod_masuk WHERE id_det_msk=?");
        $stmt->bind_param("i", $id_det_msk);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function getDetailMasukTahunProd($id_det_msk)
    {
        $stmt = $this->conn->prepare("SELECT tahunprod FROM tahunprod_masuk WHERE id_det_msk =?");
        $stmt->bind_param("i", $id_det_msk);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getTotalMasuk($month, $year)
    {
        $stmt = $this->conn->prepare("SELECT SUM(jml_msk) as total_masuk
                FROM detail_masuk 
                JOIN masuk USING(id_msk) 
                WHERE MONTH(tgl)=? AND YEAR(tgl)=? AND retur IN('0','1')");
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getTotalRetur($month, $year)
    {
        $retur = '1';
        $stmt = $this->conn->prepare("SELECT SUM(jml_msk) as total_masuk
                FROM detail_masuk 
                JOIN masuk USING(id_msk) 
                WHERE MONTH(tgl)=? AND YEAR(tgl)=? AND retur = ?");
        $stmt->bind_param("iis", $month, $year, $retur);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getLastDataKoreksiPlus()
    {
        $status = '1';
        $stmt = $this->conn->prepare("SELECT id_msk, suratJln  
                FROM masuk 
                JOIN detail_masuk USING(id_msk) 
                WHERE status_msk= ? 
                ORDER BY id_msk 
                DESC LIMIT 1");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return $stmt->get_result();
    }
}
