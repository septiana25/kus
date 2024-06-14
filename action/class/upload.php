<?php
class Upload
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function fetchKoreksiSaldo()
    {
        $stmt = $this->conn->prepare("SELECT id, kdbrg, rak, brg, qty, id_saldo FROM tmp_koreksisaldo WHERE at_update IS NULL AND at_delete IS NULL");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchKoreksiSaldoByid($id)
    {
        $stmt = $this->conn->prepare("SELECT id, kdbrg, rak, brg, qty, id_saldo FROM tmp_koreksisaldo WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDataByIdSaldoNull()
    {
        $stmt = $this->conn->prepare("SELECT id, kdbrg, rak FROM tmp_koreksisaldo WHERE id_saldo IS NULL AND at_delete IS NULL LIMIT 100");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getDataByIdSaldoNotNull()
    {
        $stmt = $this->conn->prepare("SELECT id, id_saldo, qty, brg FROM tmp_koreksisaldo WHERE id_saldo IS NOT NULL AND at_update IS NULL AND at_delete IS NULL LIMIT 100");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function update($inputs)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_koreksisaldo SET kdbrg = ?, rak = ? WHERE id = ?");
        $stmt->bind_param("ssi", $inputs['kdbrg'], $inputs['rak'], $inputs['id']);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function updateKoreksiIdSaldo($id, $id_saldo)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_koreksisaldo SET id_saldo = ? WHERE id = ?");
        $stmt->bind_param("ii", $id_saldo, $id);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function updateStatusKoreksi($id, $saldoAwal, $atUpdate)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_koreksisaldo SET saldo_awal = ?, at_update = ? WHERE id = ?");
        $stmt->bind_param("isi", $saldoAwal, $atUpdate, $id);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: upload"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }
}
