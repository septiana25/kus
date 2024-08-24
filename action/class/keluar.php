<?php
class Keluar
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function save($tgl, $noPO, $id_toko, $pembuat, $pengirim = NULL)
    {
        $stmt = $this->conn->prepare("INSERT INTO keluar (tgl, no_faktur, id_toko, pengirim, pembuat) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $tgl, $noPO, $id_toko, $pengirim, $pembuat);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }

    public function saveDetail($id_klr, $id, $jml_klr, $jam, $sisaRtr, $ket, $status_klr = NULL)
    {
        $stmt = $this->conn->prepare("INSERT INTO detail_keluar (id_klr, id, jml_klr, sisaRtr, jam, ket, status_klr) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiisss", $id_klr, $id, $jml_klr, $sisaRtr, $jam, $ket, $status_klr);
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

    public function updateSisaRtr($id_det_klr, $sisaRtr)
    {
        $stmt = $this->conn->prepare("UPDATE detail_keluar SET sisaRtr = sisaRtr - ? WHERE id_det_klr = ? AND sisaRtr >= ?");
        $stmt->bind_param("iii", $sisaRtr, $id_det_klr, $sisaRtr);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
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

    public function getByIdKlr($id_klr)
    {
        $stmt = $this->conn->prepare("SELECT id_klr, no_faktur FROM keluar WHERE id_klr = ?");
        $stmt->bind_param("i", $id_klr);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDetailKeluarTahunProd($id_det_klr)
    {
        $stmt = $this->conn->prepare("SELECT tahunprod FROM tahunprod_keluar WHERE id_det_klr =?");
        $stmt->bind_param("i", $id_det_klr);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDetailKeluar($id_det_klr)
    {
        $stmt = $this->conn->prepare("SELECT id_det_klr, id_klr, id, jml_klr, sisaRtr, ket FROM detail_keluar WHERE id_det_klr = ?");
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

    public function getSender($sender)
    {
        $stmt = $this->conn->prepare("SELECT pengirim FROM keluar WHERE pengirim LIKE ? GROUP BY pengirim LIMIT 10");
        $searchTerm = "%" . $sender . "%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getLastDataKoreksiMinus()
    {
        $status = '1';
        $stmt = $this->conn->prepare("SELECT id_klr, no_faktur  
                FROM keluar 
                JOIN detail_keluar USING(id_klr) 
                WHERE status_klr= ? 
                ORDER BY id_klr 
                DESC LIMIT 1");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return $stmt->get_result();
    }
}
