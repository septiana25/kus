<?php
class Upload
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public  function fetchKoreksiSaldo()
    {
        $status = "0";
        $stmt = $this->conn->prepare("SELECT id, kdbrg, rak, brg, qty, id_saldo, `status` FROM tmp_koreksisaldo WHERE `status` = ?");
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return $stmt->get_result();
    }

    public  function getIdSaldoNull()
    {
        $stmt = $this->conn->prepare("SELECT id, kdbrg, rak FROM tmp_koreksisaldo WHERE id_saldo IS NULL LIMIT 50");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function updateKoreksiIdSaldo($id, $id_saldo)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_koreksisaldo SET id_saldo = ? WHERE id = ?");
        $stmt->bind_param("ii", $id_saldo, $id);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }
}
