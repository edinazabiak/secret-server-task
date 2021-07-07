<?php

class Database 
{

    private $host = "sql207.epizy.com";
    private $user = "epiz_29074634";
    private $pass = "SecretServer";
    private $db_name = "epiz_29074634_secret";
    private $conn;

    public function connect() 
    {
        try {
            $this->conn = new PDO('mysql:host='. $this->host .';dbname='. $this->db_name, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }

        return $this->conn;

    }

}
