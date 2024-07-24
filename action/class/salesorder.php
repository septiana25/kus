<?php
class Salesorder
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    /**
     * Fetch all data tmp_salesorder
     * @param string 
     * 
     */
    public function getDataSalesOrderUnprocessed()
    {
        $stmt = $this->conn->prepare("SELECT id_so, no_faktur, kode_toko, toko.toko AS toko, nopol, kdbrg, barang.brg AS brg, qty, `status`
                                        FROM tmp_salesorder
                                        LEFT JOIN toko USING(kode_toko)
                                        LEFT JOIN barang USING(kdbrg)
                                        WHERE at_update IS NULL AND at_delete IS NULL
                                        ORDER BY kode_toko");
        $stmt->execute();
        return $stmt->get_result();
    }

    public function fetchSelesOrderByid($id_so)
    {
        $stmt = $this->conn->prepare("SELECT id_so, no_faktur, kode_toko, nopol, kdbrg, barang.brg AS brg, qty 
                                        FROM tmp_salesorder
                                        LEFT JOIN barang USING(kdbrg)
                                        WHERE id_so = ?");
        $stmt->bind_param("i", $id_so);
        $stmt->execute();
        return $stmt->get_result();
    }
}
