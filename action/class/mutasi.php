<?php
/* class mutasi */
class Mutasi
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function update($inputs)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_mutasi SET at_update = ? WHERE id_mutasi = ?");
        $stmt->bind_param("si", $inputs['date'], $inputs['id']);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function fetchAll()
    {
        $stmt = $this->conn->prepare("
                SELECT id_mutasi, tujuan.id_detailsaldo AS id_detailsaldo, id_brg, id_asal, brg, idrak_asal, rak_asal, idrak_tujuan, rak_tujuan, tahunprod, qty, user
                FROM (
                    SELECT id_mutasi, id_detailsaldo, id, mutasi.id_rak AS idrak_tujuan, rak AS rak_tujuan, tahunprod, qty, user
                    FROM tmp_mutasi AS mutasi
                        LEFT JOIN detail_saldo AS d_saldo USING(id_detailsaldo)
                        LEFT JOIN rak ON mutasi.id_rak = rak.id_rak
                    WHERE at_update IS NULL
                ) tujuan
                LEFT JOIN(
                    SELECT id_detailsaldo, id_brg, id AS id_asal, d_brg.id_rak AS idrak_asal, rak AS rak_asal, brg
                    FROM detail_saldo
                        LEFT JOIN detail_brg AS d_brg USING(id)
                        LEFT JOIN barang USING(id_brg)
                        LEFT JOIN rak USING(id_rak)
                ) asal ON tujuan.id_detailsaldo = asal.id_detailsaldo");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchMutasiById($id_mutasi)
    {
        $zero = 0;
        $stmt = $this->conn->prepare("SELECT id_mutasi, brg, rak AS rak_tujuan, tahunprod, qty
                                        FROM tmp_mutasi AS mutasi
                                        LEFT JOIN detail_saldo AS d_saldo USING(id_detailsaldo)
                                        LEFT JOIN detail_brg AS d_brg USING(id)
                                        LEFT JOIN barang USING(id_brg)
                                        LEFT JOIN rak ON mutasi.id_rak = rak.id_rak
                                        WHERE id_mutasi = ? AND at_update IS NULL AND at_delete IS NULL");
        $stmt->bind_param("i", $id_mutasi);
        $stmt->execute();
        return $stmt->get_result();
    }
}
