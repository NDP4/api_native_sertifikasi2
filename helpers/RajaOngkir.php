<?php

class RajaOngkir
{
    private $apiKey = "419d120b08e2598fc331a9a665ba52da";
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
