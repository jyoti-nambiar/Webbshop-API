<?php
class Database
{ //DB param
    private $host = "localhost:8001";
    private $db_name = "webshopApi";
    private $username = "root";
    private $password = "root";
    private $conn;

    //DB Connect
    function connect()
    {
        $this->conn = null;


        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "connection error" . $e->getMessage();
        }
        return $this->conn;
    }
}
