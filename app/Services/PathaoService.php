<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PathaoService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = config('services.pathao.base_url_2');
        $this->clientId = config('services.pathao.client_id_2');
        $this->clientSecret = config('services.pathao.client_secret_2');
        $this->username = config('services.pathao.username_2');
        $this->password = config('services.pathao.password_2');
    }

    // 🔹 Get access token
    public function getAccessToken()
    {
        $token = Cache::get('pathao_access_token');

        if ($token) {
            return $token;
        }

        $response = Http::asJson()->post($this->baseUrl . '/aladdin/api/v1/issue-token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'password',
            'username' => $this->username,
            'password' => $this->password,
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['access_token'])) {
            Cache::put('pathao_access_token', $data['access_token'], now()->addMinutes(55));
            return $data['access_token'];
        }

        return null;
    }

    // 🔹 Create Order
    public function createOrder(array $orderData)
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return response()->json(['error' => 'Failed to get access token'], 400);
        }
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post($this->baseUrl . '/aladdin/api/v1/orders', $orderData);

        return $response->json();
    }
    public function getCities()
    {
        $token = $this->getAccessToken();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json; charset=UTF-8',
            'Authorization' => 'Bearer ' . $token,
        ])->post($this->baseUrl . '/aladdin/api/v1/city-list', []);
        return $response->json();
    }
}
