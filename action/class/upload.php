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
        $stmt = $this->conn->prepare("SELECT * FROM tmp_koreksisaldo");
        $stmt->execute();
        return $stmt->get_result();
    }
}
