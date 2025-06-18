<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class Products
{
    private $conn;
    private $table_name = "tbl_product";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll($page = 1, $limit = 10)
    {
        $start = ($page - 1) * $limit;
        $query = "SELECT * FROM " . $this->table_name . " LIMIT ?, ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$start, $limit]);

        $products = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($products, $row);
        }

        return array(
            "status" => true,
            "data" => $products
        );
    }

    public function getById($kode)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE kode = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$kode]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return array(
                "status" => true,
                "data" => $row
            );
        }
        return array("status" => false, "message" => "Product not found");
    }

    public function search($keyword)
    {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE merk LIKE ? OR deskripsi LIKE ? OR kategori LIKE ?";
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$keyword, $keyword, $keyword]);

        $products = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($products, $row);
        }

        return array(
            "status" => true,
            "data" => $products
        );
    }
}

// Handle requests
$database = new Database();
$db = $database->getConnection();
$products = new Products($db);

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "GET") {
    if (isset($_GET['kode'])) {
        echo json_encode($products->getById($_GET['kode']));
    } elseif (isset($_GET['search'])) {
        echo json_encode($products->search($_GET['search']));
    } else {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        echo json_encode($products->getAll($page, $limit));
    }
}
