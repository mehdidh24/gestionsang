<?php
class Database {
    private $host = "localhost";
    private $db_name = "projet";
    private $username = "root";
    private $password = "";
    public $connect;

    public function connect() {
        $this->connect = null;
        try {
            $this->connect = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->connect->exec("set names utf8");
            $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->connect;
}
}
