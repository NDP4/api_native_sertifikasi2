<?php
class Database
{
    private $host = "localhost";
    private $database_name = "batiknusantara";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->database_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $this->conn;
    }
}
