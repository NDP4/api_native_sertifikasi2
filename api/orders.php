<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';

class Orders
{
    private $conn;
    private $order_table = "tbl_order";
    private $detail_table = "tbl_order_detail";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function createOrder($data)
    {
        $this->conn->beginTransaction();
        try {

            $query = "INSERT INTO " . $this->order_table . "
                     (email, tgl_order, subtotal, ongkir, total_bayar, alamat_kirim, 
                      telp_kirim, kota, provinsi, lamakirim, kodepos, metodebayar, status) 
                     VALUES (?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['email'],
                $data['subtotal'],
                $data['ongkir'],
                $data['total_bayar'],
                $data['alamat_kirim'],
                $data['telp_kirim'],
                $data['kota'],
                $data['provinsi'],
                $data['lamakirim'],
                $data['kodepos'],
                $data['metodebayar']
            ]);

            $orderId = $this->conn->lastInsertId();

            foreach ($data['items'] as $item) {
                $query = "INSERT INTO " . $this->detail_table . "
                         (trans_id, kode_brg, harga_jual, qty, bayar)
                         VALUES (?, ?, ?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([
                    $orderId,
                    $item['kode_brg'],
                    $item['harga_jual'],
                    $item['qty'],
                    $item['bayar']
                ]);
            }

            $this->conn->commit();
            return array("status" => true, "message" => "Order created successfully", "order_id" => $orderId);
        } catch (Exception $e) {
            $this->conn->rollBack();
            return array("status" => false, "message" => "Order creation failed: " . $e->getMessage());
        }
    }

    public function getOrderHistory($email)
    {
        $query = "SELECT o.*, GROUP_CONCAT(p.merk) as products 
                 FROM " . $this->order_table . " o
                 LEFT JOIN " . $this->detail_table . " od ON o.trans_id = od.trans_id
                 LEFT JOIN tbl_product p ON od.kode_brg = p.kode
                 WHERE o.email = ?
                 GROUP BY o.trans_id
                 ORDER BY o.tgl_order DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$email]);

        $orders = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($orders, $row);
        }

        return array(
            "status" => true,
            "data" => $orders
        );
    }

    public function getOrderDetail($transId, $email)
    {
        $query = "SELECT o.*, od.*, p.merk, p.foto
                 FROM " . $this->order_table . " o
                 JOIN " . $this->detail_table . " od ON o.trans_id = od.trans_id
                 JOIN tbl_product p ON od.kode_brg = p.kode
                 WHERE o.trans_id = ? AND o.email = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$transId, $email]);

        $items = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($items, $row);
        }

        return array(
            "status" => true,
            "data" => $items
        );
    }
}

$database = new Database();
$db = $database->getConnection();
$orders = new Orders($db);

$data = json_decode(file_get_contents("php://input"), true);
$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "POST") {
    echo json_encode($orders->createOrder($data));
} elseif ($requestMethod === "GET") {
    if (isset($_GET['email']) && isset($_GET['trans_id'])) {
        echo json_encode($orders->getOrderDetail($_GET['trans_id'], $_GET['email']));
    } elseif (isset($_GET['email'])) {
        echo json_encode($orders->getOrderHistory($_GET['email']));
    } else {
        echo json_encode(array("status" => false, "message" => "Missing required parameters"));
    }
}
