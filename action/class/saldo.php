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
            return ['success' => false, 'message' => "Execute failed: saldo"];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function updateSaldoByKoreksi($id_saldo, $qty)
    {
        $stmt = $this->conn->prepare("UPDATE saldo SET saldo_akhir = ? WHERE id_saldo = ?");
        $stmt->bind_param("ii", $qty, $id_saldo);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: saldo", 'error' => $stmt->error];
        }
        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function updateSaldoPlus($id_saldo, $qty)
    {

        $stmt = $this->conn->prepare("UPDATE saldo SET saldo_akhir = saldo_akhir + ? WHERE id_saldo = ?");
        $stmt->bind_param("ii", $qty, $id_saldo);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function updateSaldoMinus($id_saldo, $qty)
    {
        $stmt = $this->conn->prepare("UPDATE saldo SET saldo_akhir = saldo_akhir - ? WHERE id_saldo = ? AND saldo_akhir >= ?");
        $stmt->bind_param("iii", $qty, $id_saldo, $qty);
        $stmt->execute();

        if ($stmt->affected_rows == 0) {
            $this->conn->rollback();
            return ['success' => false, 'message' => "Saldo tidak cukup atau data tidak ditemukan"];
        }
        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function getAllSaldo($month, $year)
    {
        $saldoZeo = 0;
        $stmt = $this->conn->prepare("SELECT b.id_brg AS id_brg, nourt, kdbrg, b.brg AS brg, d.rak AS rak, saldo_awal, saldo_akhir, kat, id, tahunprod, jumlah
        FROM(
        SELECT id_brg, id, rak, saldo_awal, saldo_akhir, tgl, tahunprod, IFNULL(jumlah, '-') AS jumlah
        FROM detail_brg
        LEFT JOIN detail_saldo USING(id)
        LEFT JOIN rak USING(id_rak)
        LEFT JOIN saldo USING(id)
        WHERE MONTH(tgl)= ? AND YEAR(tgl)= ?
        )d
        LEFT JOIN (
        SELECT id_brg, kdbrg, brg, nourt, kat
        FROM barang
        JOIN kat USING(id_kat)
        )b ON b.id_brg=d.id_brg WHERE saldo_akhir != ? ORDER BY rak, b.brg ASC");
        $stmt->bind_param("iii", $month, $year, $saldoZeo);
        $stmt->execute();
        return $stmt->get_result();
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

    public function getSaldoByIdSaldo($id_saldo)
    {
        $stmt = $this->conn->prepare("SELECT id_saldo, id, saldo_akhir FROM saldo WHERE id_saldo = ?");
        $stmt->bind_param("i", $id_saldo);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getSaldoByidJoinDetail($id, $month, $year)
    {

        $stmt = $this->conn->prepare(" SELECT id_saldo, saldo.id, brg, rak, saldo_akhir, CAST(IFNULL(subtotal, 0) AS UNSIGNED) AS subtotal
                FROM (
                SELECT id_saldo, id, rak, brg, saldo_akhir
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

    public function getTotalSaldo($month, $year)
    {
        $stmt = $this->conn->prepare("SELECT SUM(saldo_awal) AS saldo_awal, SUM(saldo_akhir) AS saldo_akhir 
                            FROM saldo WHERE MONTH(tgl) = ? AND YEAR(tgl) = ?");
        $stmt->bind_param("ii", $month, $year);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getSaldoByAnyKodeBarang($kdbrg, $month, $year)
    {
        $kdbrg = array_map(function ($value) {
            return "'" . $this->conn->real_escape_string($value) . "'";
        }, $kdbrg);

        $kdbrg = implode(',', $kdbrg);
        $query = "SELECT id_detailsaldo, id_saldo, saldo.id, kdbrg, brg, rak, tahunprod, jumlah
        FROM (
            SELECT id_saldo, id, rak, kdbrg, brg, saldo_akhir
            FROM detail_brg
            LEFT JOIN saldo USING(id)
            LEFT JOIN barang USING(id_brg)
            LEFT JOIN rak USING(id_rak)
            WHERE MONTH(tgl) = $month AND YEAR(tgl) =  $year AND saldo_akhir != 0 AND kdbrg IN ($kdbrg)
        ) saldo
        LEFT JOIN (
            SELECT id_detailsaldo, id, tahunprod, jumlah
            FROM detail_saldo
            WHERE jumlah != 0
        ) detailsaldo ON saldo.id = detailsaldo.id";

        $stmt = $this->conn->query($query);
        return $stmt;
    }
}
