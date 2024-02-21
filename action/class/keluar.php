<?php
class Keluar
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function save($tgl, $noPO, $nama)
    {
        $stmt = $this->conn->prepare("INSERT INTO keluar (tgl, suratJln, pembuat) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $tgl, $noPO, $nama);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }

    public function saveTahunProd($id_det_klr, $tahunprod)
    {
        $stmt = $this->conn->prepare("INSERT INTO tahunprod_keluar (id_det_klr, tahunprod) VALUES (?, ?)");
        $stmt->bind_param("is", $id_det_klr, $tahunprod);
        $success = $stmt->execute();
        return ['success' => $success];
    }

    public function deleteDetailKeluarTahunProd($id_det_klr)
    {
        $stmt = $this->conn->prepare("DELETE FROM tahunprod_keluar WHERE id_det_klr=?");
        $stmt->bind_param("i", $id_det_klr);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function getDetailKeluarTahunProd($id_det_klr)
    {
        $stmt = $this->conn->prepare("SELECT tahunprod FROM tahunprod_keluar WHERE id_det_klr =?");
        $stmt->bind_param("i", $id_det_klr);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getTotalKeluar($month, $year)
    {
        $stmt = $this->conn->prepare("SELECT SUM(jml_klr) as total_keluar
                FROM detail_keluar
                JOIN keluar USING(id_klr) 
                WHERE MONTH(tgl)= ? AND YEAR(tgl)= ?");
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        return $stmt->get_result();
    }
}
