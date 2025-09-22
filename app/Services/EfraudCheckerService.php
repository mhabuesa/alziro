<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class EfraudCheckerService
{
    protected string $base;
    protected string $apiKey;
    protected string $domain;

    public function __construct()
    {
        $this->base   = rtrim(config('services.efraud.base'), '/');
        $this->apiKey = config('services.efraud.apiKey');
        $this->domain = config('services.efraud.domain');
    }

    public function checkPhone(string $phone): array
    {
        $payload = [
            'apiKey' => $this->apiKey,
            'domain' => $this->domain,
            'phone'  => $phone,
        ];

        $response = Http::acceptJson()
            ->post("{$this->base}/shield/fraud-checker", $payload);

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'success' => false,
            'message' => $response->body(),
            'status'  => $response->status(),
        ];
    }
}
