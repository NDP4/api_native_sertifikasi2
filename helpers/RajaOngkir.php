<?php

class RajaOngkir
{
    private $apiKey = "YOUR_API_KEY";
    private $baseUrl = "https://api.rajaongkir.com/starter/";
    private $originCity = "391";

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
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers
        ];

        if ($method === 'POST') {
            $curl_options[CURLOPT_POSTFIELDS] = http_build_query($data);
        }

        curl_setopt_array($curl, $curl_options);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err) {
            return [
                'status' => false,
                'code' => $httpCode,
                'message' => $err
            ];
        }

        $result = json_decode($response, true);

        if (!$result) {
            return [
                'status' => false,
                'code' => $httpCode,
                'message' => 'Failed to decode response'
            ];
        }

        return $result;
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
        if (!$destination || !$weight) {
            return [
                'status' => false,
                'message' => 'Destination and weight are required'
            ];
        }

        $data = [
            'origin' => $this->originCity,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => 'jne'
        ];

        $result = $this->curlRequest('cost', $data, 'POST');

        if (isset($result['rajaongkir']) && isset($result['rajaongkir']['results'])) {
            return [
                'status' => true,
                'data' => $result['rajaongkir']
            ];
        }

        return [
            'status' => false,
            'message' => 'Failed to calculate shipping cost',
            'raw_response' => $result
        ];
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
