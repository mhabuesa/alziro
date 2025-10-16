<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PathaoService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PathaoController extends Controller
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
    protected function getAccessToken()
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

    // 🔹 Get City List
    public function getCities()
    {
        $token = $this->getAccessToken();

        if (!$token) {
            return response()->json(['error' => 'Failed to get access token'], 400);
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json; charset=UTF-8',
            'Authorization' => 'Bearer ' . $token,
        ])->get($this->baseUrl . '/aladdin/api/v1/city-list');

        return response()->json($response->json(), $response->status());
    }

    // 🔹 Add Order to Pathao
    public function addOrder(Request $request, PathaoService $pathao)
    {
        // Example: You can get these from your own $order or $request
        $payload = [
            'store_id'           => 336765, // Pathao store ID
            'merchant_order_id'  => '001102',
            'recipient_name'     => 'Abu Esa',
            'recipient_phone'    => '01706944396',
            'recipient_address'  => 'House 12, Road 5, Dhanmondi',
            'recipient_city'     => '1',
            'recipient_zone'     => '318',
            'recipient_area'     => '17128',
            'delivery_type'      => 48, // 48=Normal, 12=On Demand
            'item_type'          => 2,  // 1=Document, 2=Parcel
            'special_instruction' => 'Handle carefully',
            'item_quantity'      => 1,
            'item_weight'        => 0.5,
            'item_description'   =>  'Product Item',
            'amount_to_collect'  => '1000',
        ];

        $response = $pathao->createOrder($payload);

        return response()->json($response);
    }
}
