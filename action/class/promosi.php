<?php
class Promosi
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Fetch all data ekspedisi
     * @param string 
     * 
     */
    public function fetchAll()
    {
        $stmt = $this->conn->prepare("SELECT id_promo, divisi, item, jenis, saldo, note
                                        FROM promosi
                                        WHERE at_delete IS NULL
                                        ORDER BY jenis");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchAllPromosiMasuk()
    {
        $stmt = $this->conn->prepare("SELECT id_promsk, no_tran, promosi_masuk.divisi AS divisi, promosi_masuk.id_promo AS id_promo, item, qty, promosi_masuk.at_create AS at_create
                                        FROM promosi_masuk 
                                        LEFT JOIN promosi USING(id_promo)
                                        WHERE promosi_masuk.at_delete IS NULL
                                        ORDER BY promosi_masuk.id_promsk DESC");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchAllPromosiKeluar()
    {
        $stmt = $this->conn->prepare("SELECT id_proklr, no_trank, promosi_keluar.divisi AS divisi, promosi_keluar.id_promo AS id_promo, sales, toko, item, qty, promosi_keluar.at_create AS at_create
                                        FROM promosi_keluar 
                                        LEFT JOIN promosi USING(id_promo)
                                        LEFT JOIN toko USING(id_toko)
                                        WHERE promosi_keluar.at_delete IS NULL
                                        ORDER BY promosi_keluar.id_proklr DESC");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchPromosiByid($id_promo)
    {
        $stmt = $this->conn->prepare("SELECT id_promo, divisi, item, jenis, saldo, note
                                        FROM promosi
                                        WHERE id_promo = ? AND at_delete IS NULL");
        $stmt->bind_param("i", $id_promo);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPromosiByItem($item)
    {
        $stmt = $this->conn->prepare("SELECT id_promo, item, jenis, saldo
                                        FROM promosi
                                        WHERE item = ? AND at_delete IS NULL");
        $stmt->bind_param("s", $item);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPromosiByDivisi($divisi)
    {
        $stmt = $this->conn->prepare("SELECT id_promo, item, jenis, saldo
                                        FROM promosi
                                        WHERE divisi = ? AND at_delete IS NULL");
        $stmt->bind_param("s", $divisi);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPromosiKeluarById($id_proklr)
    {
        $stmt = $this->conn->prepare("SELECT no_trank, id_toko, sales, id_promo, qty, at_create
                                        FROM promosi_keluar 
                                        WHERE id_proklr = ? AND at_delete IS NULL");
        $stmt->bind_param("s", $id_proklr);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPromosiByNoTran($no_tran)
    {
        $stmt = $this->conn->prepare("SELECT no_trank, id_toko, sales, id_promo, qty, at_create
                                        FROM promosi_keluar 
                                        WHERE no_trank = ? AND at_delete IS NULL
                                        ORDER BY at_create ASC LIMIT 1");
        $stmt->bind_param("s", $no_tran);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getPromosiByNoTranPrint($no_tran)
    {
        $stmt = $this->conn->prepare("SELECT no_trank, promosi_keluar.divisi AS divisi, promosi_keluar.id_promo AS id_promo, sales, toko, alamat, item, qty, promosi_keluar.note, promosi_keluar.at_create AS at_create
                                        FROM promosi_keluar 
                                        LEFT JOIN promosi USING(id_promo)
                                        LEFT JOIN toko USING(id_toko)
                                        WHERE no_trank = ? AND promosi_keluar.at_delete IS NULL");
        $stmt->bind_param("s", $no_tran);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function insert($inputs)
    {

        $stmt = $this->conn->prepare("INSERT INTO promosi (divisi, item, jenis, note, user) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $inputs['divisi'], $inputs['item'], $inputs['jenis'], $inputs['note'], $inputs['user']);
        return $stmt->execute();
    }

    public function insertPromosiMasuk($inputs)
    {
        $stmt = $this->conn->prepare("INSERT INTO promosi_masuk (no_tran, id_promo, divisi, qty, note, user) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $inputs['no_tran'], $inputs['item'], $inputs['divisi'], $inputs['qty'], $inputs['note'], $inputs['user']);
        return $stmt->execute();
    }

    public function insertPromosiKeluar($inputs)
    {
        $stmt = $this->conn->prepare("INSERT INTO promosi_keluar (no_trank, id_promo, divisi, sales, id_toko, qty, note, user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $inputs['no_tran'], $inputs['item'], $inputs['divisi'], $inputs['sales'], $inputs['toko'], $inputs['qty'], $inputs['note'], $inputs['user']);
        return $stmt->execute();
    }

    public function update($inputs)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_salesorder SET nopol = ?, kdbrg = ?, kode_toko = ?, qty = ? WHERE id_so = ?");
        $stmt->bind_param("sssii", $inputs['nopol'], $inputs['kdbrg'], $inputs['kode_toko'], $inputs['qty'], $inputs['id_so']);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function updateSaldo($id_promo, $qty)
    {
        $stmt = $this->conn->prepare("UPDATE promosi SET saldo = saldo + ? WHERE id_promo = ?");
        $stmt->bind_param("ss", $qty, $id_promo);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function updateSaldoKeluar($id_promo, $qty)
    {
        $stmt = $this->conn->prepare("UPDATE promosi SET saldo = saldo - ? WHERE id_promo = ? AND saldo >= ?");
        $stmt->bind_param("sss", $qty, $id_promo, $qty);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }

    public function delete($id_so, $atDelete)
    {
        $stmt = $this->conn->prepare("UPDATE tmp_salesorder SET at_delete = ? WHERE id_so = ?");
        $stmt->bind_param("si", $atDelete, $id_so);
        $stmt->execute();
        if ($stmt->affected_rows == 0) {
            return ['success' => false, 'message' => "Execute failed: "];
        }

        return ['success' => true, 'affected_rows' => $stmt->affected_rows];
    }
}
