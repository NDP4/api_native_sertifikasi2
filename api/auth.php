<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class Auth
{
    private $conn;
    private $table_name = "tbl_pelanggan";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $row['PASSWORD'])) {
                return array(
                    "status" => true,
                    "message" => "Login successful",
                    "data" => array(
                        "id" => $row['id'],
                        "nama" => $row['nama'],
                        "email" => $row['email']
                    )
                );
            }
        }
        return array("status" => false, "message" => "Invalid credentials");
    }

    public function register($data)
    {
        $query = "INSERT INTO " . $this->table_name . " 
                 (nama, alamat, kota, provinsi, kodepos, telp, email, PASSWORD) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        $password_hash = password_hash($data['password'], PASSWORD_DEFAULT);

        try {
            $stmt->execute([
                $data['nama'],
                $data['alamat'],
                $data['kota'],
                $data['provinsi'],
                $data['kodepos'],
                $data['telp'],
                $data['email'],
                $password_hash
            ]);
            return array("status" => true, "message" => "Registration successful");
        } catch (PDOException $e) {
            return array("status" => false, "message" => "Registration failed: " . $e->getMessage());
        }
    }
}

$database = new Database();
$db = $database->getConnection();
$auth = new Auth($db);

$data = json_decode(file_get_contents("php://input"), true);
$requestMethod = $_SERVER["REQUEST_METHOD"];
$endpoint = basename($_SERVER['PHP_SELF']);

if ($endpoint === "login.php" && $requestMethod === "POST") {
    if (isset($data['email']) && isset($data['password'])) {
        echo json_encode($auth->login($data['email'], $data['password']));
    } else {
        echo json_encode(array("status" => false, "message" => "Missing required fields"));
    }
} elseif ($endpoint === "register.php" && $requestMethod === "POST") {
    if (isset($data['email']) && isset($data['password']) && isset($data['nama'])) {
        echo json_encode($auth->register($data));
    } else {
        echo json_encode(array("status" => false, "message" => "Missing required fields"));
    }
}
