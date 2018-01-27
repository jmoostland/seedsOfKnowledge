<?php

class Database {

    private $hostname = 'localhost';
    private $databasenaam = 'seedsofknowledge';
    private $username = 'root';
    private $password = 'root';
    public $conn;

    public function __construct() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->databasenaam);
        } catch (mysqli_sql_exception $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

}
