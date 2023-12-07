<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CloverController extends Controller
{
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://apisandbox.dev.clover.com/v3/merchants/001NZQHXZQ1K1/';
        $this->apiKey = 'd9afaf8c-8d32-621a-92cb-4b4f5f5432de';
    }

    public function createOrder(Request $request)
    {
        $client = new Client();

        $response = $client->post($this->baseUrl . 'orders', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                // Your order details here
            ],
        ]);

        $order = json_decode($response->getBody());

        return response()->json($order);
    }

    public function processPayment(Request $request, $orderID)
    {
        $client = new Client();

        $response = $client->post($this->baseUrl . "orders/{$orderID}/pay", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                // Payment details here
            ],
        ]);

        $paymentResponse = json_decode($response->getBody());

        return response()->json($paymentResponse);
    }

}
