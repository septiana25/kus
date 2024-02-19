<?php
class DetailSaldo
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function save($id, $tahunprod, $jml)
    {

        $stmt = $this->conn->prepare("INSERT INTO detail_saldo (id, tahunprod, jumlah) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $id, $tahunprod, $jml);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }

    public function update($id_detailsaldo, $jml)
    {
        $stmt = $this->conn->prepare("UPDATE detail_saldo SET jumlah = ? WHERE id_detailsaldo = ?");

        if ($stmt === false) {
            return ['success' => false, 'message' => "Prepare failed: " . $this->conn->error];
        }
        $stmt->bind_param("ii", $jml, $id_detailsaldo);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function getDetailSaldoByidDetailsaldo($id_detailsaldo)
    {
        $stmt = $this->conn->prepare("SELECT id_detailsaldo, id, jumlah, tahunprod FROM detail_saldo WHERE id_detailsaldo = ?");
        $stmt->bind_param("i", $id_detailsaldo);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDetailSaldoByid($id)
    {
        $saldoZeo = 0;
        $stmt = $this->conn->prepare("SELECT id_detailsaldo, id, jumlah, tahunprod FROM detail_saldo WHERE id = ? AND jumlah != ? ORDER BY tahunprod ASC");
        $stmt->bind_param("ii", $id, $saldoZeo);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDetailSaldoByidAndYearProd($id, $tahunprod)
    {
        $stmt = $this->conn->prepare("SELECT id_detailsaldo, id, jumlah, tahunprod FROM detail_saldo WHERE id = ? AND tahunprod = ?");
        $stmt->bind_param("is", $id, $tahunprod);
        $stmt->execute();
        return $stmt->get_result();
    }
}
