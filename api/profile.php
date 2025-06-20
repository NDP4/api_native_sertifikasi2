<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, PUT");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class Profile
{
    private $conn;
    private $table_name = "tbl_pelanggan";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getProfile($id)
    {
        $query = "SELECT id, nama, alamat, kota, provinsi, kodepos, telp, email, foto 
                 FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            return array(
                "status" => true,
                "data" => $row
            );
        }
        return array("status" => false, "message" => "Profile not found");
    }

    public function updateProfile($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " 
                 SET nama = ?, alamat = ?, kota = ?, provinsi = ?, 
                     kodepos = ?, telp = ?";

        $params = [
            $data['nama'],
            $data['alamat'],
            $data['kota'],
            $data['provinsi'],
            $data['kodepos'],
            $data['telp']
        ];

        if (isset($data['password']) && !empty($data['password'])) {
            $query .= ", PASSWORD = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if (isset($data['foto']) && !empty($data['foto'])) {
            $query .= ", foto = ?";
            $params[] = $data['foto'];
        }

        $query .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $this->conn->prepare($query);

        try {
            $stmt->execute($params);
            return array("status" => true, "message" => "Profile updated successfully");
        } catch (PDOException $e) {
            return array("status" => false, "message" => "Profile update failed: " . $e->getMessage());
        }
    }
}

$database = new Database();
$db = $database->getConnection();
$profile = new Profile($db);

$data = json_decode(file_get_contents("php://input"), true);
$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "GET" && isset($_GET['id'])) {
    echo json_encode($profile->getProfile($_GET['id']));
} elseif ($requestMethod === "PUT" && isset($_GET['id'])) {
    echo json_encode($profile->updateProfile($_GET['id'], $data));
} else {
    echo json_encode(array("status" => false, "message" => "Invalid request or missing ID"));
}
