<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class Products
{
    private $conn;
    private $table_name = "tbl_product";
    private $upload_path = "../uploads/products/";
    private $base_url;

    private function getBaseUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . $host . "/uploads/products/";
    }

    public function __construct($db)
    {
        $this->conn = $db;
        $this->base_url = $this->getBaseUrl();
        if (!file_exists($this->upload_path)) {
            mkdir($this->upload_path, 0777, true);
        }
    }

    private function getImageUrl($filename)
    {
        if (!empty($filename)) {
            return $this->base_url . $filename;
        }
        return null;
    }

    public function getAll($page = 1, $limit = 10)
    {
        $page = (int)$page;
        $limit = (int)$limit;
        $start = ($page - 1) * $limit;

        $count_query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $count_stmt = $this->conn->prepare($count_query);
        $count_stmt->execute();
        $total_records = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

        $query = "SELECT * FROM " . $this->table_name . " LIMIT :start, :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':start', $start, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $products = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (isset($row['foto'])) {
                $row['foto_url'] = $this->getImageUrl($row['foto']);
            }
            array_push($products, $row);
        }

        return array(
            "status" => true,
            "data" => $products,
            "pagination" => array(
                "page" => $page,
                "limit" => $limit,
                "total_records" => (int)$total_records,
                "total_pages" => ceil($total_records / $limit)
            )
        );
    }

    public function getById($kode)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE kode = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$kode]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (isset($row['foto'])) {
                $row['foto_url'] = $this->getImageUrl($row['foto']);
            }
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
            if (isset($row['foto'])) {
                $row['foto_url'] = $this->getImageUrl($row['foto']);
            }
            array_push($products, $row);
        }

        return array(
            "status" => true,
            "data" => $products
        );
    }

    public function uploadImage($file)
    {
        $target_file = $this->upload_path . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            return array("status" => false, "message" => "File is not an image.");
        }

        if ($file["size"] > 500000) {
            return array("status" => false, "message" => "Sorry, your file is too large.");
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            return array("status" => false, "message" => "Sorry, only JPG, JPEG, PNG files are allowed.");
        }

        $new_filename = uniqid() . '.' . $imageFileType;
        $target_file = $this->upload_path . $new_filename;

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return array(
                "status" => true,
                "filename" => $new_filename,
                "message" => "The file has been uploaded."
            );
        } else {
            return array("status" => false, "message" => "Sorry, there was an error uploading your file.");
        }
    }
}

$database = new Database();
$db = $database->getConnection();
$products = new Products($db);

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "POST" && isset($_FILES['foto'])) {
    echo json_encode($products->uploadImage($_FILES['foto']));
} elseif ($requestMethod === "GET") {
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
