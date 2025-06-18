<?php

class RajaOngkir
{
    private $apiKey = "YOUR_API_KEY";
    private $baseUrl = "https://api.rajaongkir.com/starter/";
    private $originCity = "444";

    public function __construct() {}

    private function curlRequest($endpoint, $data = [], $method = 'GET')
    {
        $curl = curl_init();
        $url = $this->baseUrl . $endpoint;

        $headers = [
            "key: " . $this->apiKey,
            "content-type: application/x-www-form-urlencoded"
        ];

        $curl_options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0
        ];

        if ($method === 'POST') {
            $curl_options[CURLOPT_POST] = true;
            $curl_options[CURLOPT_POSTFIELDS] = http_build_query($data);
        }

        curl_setopt_array($curl, $curl_options);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                'status' => false,
                'message' => $err
            ];
        }

        return json_decode($response, true);
    }

    public function getProvinces()
    {
        return $this->curlRequest('province');
    }

    public function getCities($provinceId = null)
    {
        $endpoint = 'city';
        if ($provinceId) {
            $endpoint .= '?province=' . $provinceId;
        }
        return $this->curlRequest($endpoint);
    }

    public function calculateShipping($destination, $weight)
    {
        $data = [
            'origin' => $this->originCity,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => 'jne'
        ];

        return $this->curlRequest('cost', $data, 'POST');
    }
}

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $rajaOngkir = new RajaOngkir();

    switch ($_GET['action']) {
        case 'provinces':
            echo json_encode($rajaOngkir->getProvinces());
            break;

        case 'cities':
            $provinceId = isset($_GET['province']) ? $_GET['province'] : null;
            echo json_encode($rajaOngkir->getCities($provinceId));
            break;

        case 'cost':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);
                if (isset($data['destination']) && isset($data['weight'])) {
                    echo json_encode($rajaOngkir->calculateShipping($data['destination'], $data['weight']));
                } else {
                    echo json_encode(['error' => 'Missing required parameters']);
                }
            } else {
                echo json_encode(['error' => 'Method not allowed']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid action']);
    }
    exit;
}
