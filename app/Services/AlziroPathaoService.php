<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AlziroPathaoService
{
    protected $baseUrl;
    protected $clientId;
    protected $clientSecret;
    protected $username;
    protected $password;

    public function __construct()
    {
        $this->baseUrl = config('services.pathao.alziro.base_url');
        $this->clientId = config('services.pathao.alziro.client_id');
        $this->clientSecret = config('services.pathao.alziro.client_secret');
        $this->username = config('services.pathao.alziro.username');
        $this->password = config('services.pathao.alziro.password');
    }

    /**
     * 🔹 Get Access Token (auto-refresh using refresh token)
     */
    public function getAccessToken()
    {
        // Check if access token exists in cache
        $token = Cache::get('pathao_access_token_alziro');
        if ($token) {
            return $token;
        }

        // Check if refresh token exists
        $refreshToken = Cache::get('pathao_refresh_token_alziro');
        if ($refreshToken) {
            $token = $this->refreshAccessToken($refreshToken);
            if ($token) {
                return $token;
            }
        }

        // Generate token using username/password if no valid refresh token
        return $this->generateToken();
    }

    /**
     * 🔹 Generate token using username/password
     */
    protected function generateToken()
    {
        $response = Http::asJson()->post($this->baseUrl . '/aladdin/api/v1/issue-token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'password',
            'username' => $this->username,
            'password' => $this->password,
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['access_token'], $data['refresh_token'])) {
            // Save access token and refresh token in cache
            Cache::put('pathao_access_token_alziro', $data['access_token'], now()->addMinutes(55));
            Cache::put('pathao_refresh_token_alziro', $data['refresh_token'], now()->addDays(7)); // refresh token validity
            return $data['access_token'];
        }

        return null;
    }

    /**
     * 🔹 Refresh access token using refresh token
     */
    protected function refreshAccessToken($refreshToken)
    {
        $response = Http::asJson()->post($this->baseUrl . '/aladdin/api/v1/issue-token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);

        $data = $response->json();

        if ($response->successful() && isset($data['access_token'])) {
            Cache::put('pathao_access_token_alziro', $data['access_token'], now()->addMinutes(55));

            // Update refresh token if API returns new one
            if (isset($data['refresh_token'])) {
                Cache::put('pathao_refresh_token_alziro', $data['refresh_token'], now()->addDays(7));
            }

            return $data['access_token'];
        }

        return null;
    }

    /**
     * 🔹 Get Cities
     */
    public function getCities()
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['error' => 'Failed to get access token'];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json; charset=UTF-8',
            'Authorization' => 'Bearer ' . $token,
        ])->get($this->baseUrl . '/aladdin/api/v1/city-list', []);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'status' => $response->status(),
            'body' => $response->body(),
        ];
    }
    public function getZones($city_id)
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['error' => 'Failed to get access token'];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json; charset=UTF-8',
            'Authorization' => 'Bearer ' . $token,
        ])->get($this->baseUrl . '/aladdin/api/v1/cities/' . $city_id . '/zone-list', []);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'status' => $response->status(),
            'body' => $response->body(),
        ];
    }
    public function getAreas($zone_id)
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['error' => 'Failed to get access token'];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json; charset=UTF-8',
            'Authorization' => 'Bearer ' . $token,
        ])->get($this->baseUrl . '/aladdin/api/v1/zones/' . $zone_id . '/area-list', []);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'status' => $response->status(),
            'body' => $response->body(),
        ];
    }

    /**
     * 🔹 Create Order
     */
    public function createOrder(array $orderData)
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return ['error' => 'Failed to get access token'];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post($this->baseUrl . '/aladdin/api/v1/orders', $orderData);

        return $response->json();
    }
}
