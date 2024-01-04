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
            throw new Exception("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("ii", $saldoAkhir, $id);

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function getSaldoByLastDate()
    {
        $stmt = $this->conn->prepare("SELECT MONTH(tgl) FROM saldo ORDER BY id_saldo DESC LIMIT 0,1");
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data ? $data['MONTH(tgl)'] : null;
    }

    public function getSaldoByid($id, $month, $year)
    {
        $stmt = $this->conn->prepare("SELECT id_saldo, saldo_awal, saldo_akhir FROM saldo WHERE id = ? AND MONTH(tgl) = ? AND YEAR(tgl) = ?");
        $stmt->bind_param("iii", $id, $month, $year);
        $stmt->execute();
        return $stmt->get_result();
    }
}
