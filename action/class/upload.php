<?php
class Upload
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Fetch data koreksi saldo
     * @param string $type 1 = koreksi saldo akhir, 2 = koreksi plus, 3 = koreksi minus
     * 
     */
    public function fetchKoreksiSaldo($type)
    {
        $stmt = $this->conn->prepare("SELECT id, kdbrg, rak, brg, qty, tahunprod, id_saldo, id_detailsaldo FROM tmp_koreksisaldo WHERE `type` = ? AND at_update IS NULL AND at_delete IS NULL");
        $stmt->bind_param("s", $type);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchKoreksiSaldoByid($id)
    {
        $stmt = $this->conn->prepare("SELECT id, kdbrg, rak, brg, qty, tahunprod, id_saldo FROM tmp_koreksisaldo WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Fetch data is null
     * @param string $type 1 = koreksi saldo akhir, 2 = koreksi plus, 3 = koreksi minus
     */
    public function getDataByIdSaldoNull($type)
    {
        $stmt = $this->conn->prepare("SELECT id, kdbrg, rak, tahunprod FROM tmp_koreksisaldo WHERE `type` = ? AND id_saldo IS NULL AND at_delete IS NULL LIMIT 100");
        $stmt->bind_param("s", $type);
        $stmt->execute();
        return $stmt->get_result();
    }

    /**
     * Fetch data is not null
     * @param string $type 1 = koreksi saldo akhir, 2 = koreksi plus, 3 = koreksi minus
     */
    public function getDataByIdSaldoNotNull($type)
    {
        $stmt = $this->conn->prepare("SELECT id, id_saldo, id_detailsaldo, qty, brg, tahunprod 
                FROM tmp_koreksisaldo 
                WHERE `type` = ? AND id_saldo IS NOT NULL AND id_detailsaldo IS NOT NULL AND at_update IS NULL AND at_delete IS NULL 
                LIMIT 100");
        $stmt->bind_param("s", $type);
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

    public function updateKoreksiIdSaldo($id, $id_saldo, $id_detailsaldo)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_koreksisaldo SET id_saldo = ?, id_detailsaldo = ? WHERE id = ?");
        $stmt->bind_param("iii", $id_saldo, $id_detailsaldo, $id);
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

    public function delete($id, $atDelete)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_koreksisaldo SET at_delete = ? WHERE id = ?");
        $stmt->bind_param("si", $atDelete, $id);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }
}
