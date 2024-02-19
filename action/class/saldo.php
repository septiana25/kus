<?php
class Saldo
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function save($id, $tgl, $saldoAkhir)
    {
        $stmt = $this->conn->prepare("INSERT INTO saldo (id, tgl, saldo_awal, saldo_akhir) VALUES (?, ?, ?, ?)");
        $saldoAwal = 0;
        $stmt->bind_param("isii", $id, $tgl, $saldoAwal, $saldoAkhir);
        $success = $stmt->execute();
        return ['success' => $success, 'id' => $this->conn->insert_id];
    }

    public function update($id, $saldoAkhir)
    {
        $stmt = $this->conn->prepare("UPDATE saldo SET saldo_akhir = ? WHERE id_saldo = ?");

        if ($stmt === false) {
            return ['success' => false, 'message' => "Prepare failed: " . $this->conn->error];
        }
        $stmt->bind_param("ii", $saldoAkhir, $id);

        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function getSaldoByLastDate()
    {
        $stmt = $this->conn->prepare("SELECT tgl FROM saldo ORDER BY id_saldo DESC LIMIT 0,1");

        if ($stmt === false) {
            return ['success' => false, 'message' => "Prepare failed: " . $this->conn->error];
        }

        if (!$stmt->execute()) {
            return ['success' => false, 'message' => "Execute failed: " . $stmt];
        }

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data ? $data['tgl'] : null;
    }

    public function getSaldoByid($id, $month, $year)
    {
        $stmt = $this->conn->prepare("SELECT id_saldo, saldo_awal, saldo_akhir FROM saldo WHERE id = ? AND MONTH(tgl) = ? AND YEAR(tgl) = ?");
        $stmt->bind_param("iii", $id, $month, $year);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getSaldoByidJoinDetail($id, $month, $year)
    {

        $stmt = $this->conn->prepare(" SELECT saldo.id, brg, rak, saldo_akhir, CAST(IFNULL(subtotal, 0) AS UNSIGNED) AS subtotal
                FROM (
                SELECT id, rak, brg, saldo_akhir
                FROM detail_brg
                LEFT JOIN saldo USING(id)
                LEFT JOIN barang USING(id_brg)
                LEFT JOIN rak USING(id_rak)
                WHERE id = ? AND MONTH(tgl)=?  AND YEAR(tgl)=?
                ) saldo
                LEFT JOIN (
                SELECT id, SUM(jumlah) AS subtotal
                FROM detail_saldo 
                WHERE id = ?
                ) detailsaldo ON saldo.id = detailsaldo.id");
        $stmt->bind_param("iiii", $id, $month, $year, $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getSaldoByidJoinDetailByDate($id, $month, $year)
    {
        $saldoZeo = 0;
        $stmt = $this->conn->prepare("SELECT id_detailsaldo, rak,  tahunprod, jumlah
        FROM detail_saldo
        LEFT JOIN detail_brg USING(id)
        LEFT JOIN rak USING(id_rak)
        LEFT JOIN saldo USING(id)
        WHERE id_brg = ? AND MONTH(tgl) = ? AND YEAR(tgl) = ? AND jumlah != ?");
        $stmt->bind_param("iiii", $id, $month, $year, $saldoZeo);
        $stmt->execute();
        return $stmt->get_result();
    }
}
