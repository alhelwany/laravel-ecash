<?php

namespace Alhelwany\LaravelEcash\Utilities;

use Alhelwany\LaravelEcash\Exceptions\EcashServerErrorException;
use Alhelwany\LaravelEcash\Exceptions\InvalidConfigurationException;
use Http;

class ReverseUtility
{
    /**
     * Get the access token from the Ecash server
     * @throws \Alhelwany\LaravelEcash\Exceptions\InvalidConfigurationException
     */
    private function getAccessToken()
    {
        $response = Http::asForm()->post(config('ecash.gatewayUrl') . '/connect/token', [
            'grant_type' => 'client_credentials',
            'scope' => 'TransactionManagementService',
            'client_id' => config('ecash.merchantId'),
            'client_secret' => config('ecash.merchantSecret'),
            'username' => config('ecash.username'),
            'password' => config('ecash.password'),
        ]);

        if ($response->failed())
            throw new InvalidConfigurationException;

        return $response->json()['access_token'];
    }

    /**
     * Calls the Ecash server to reverse a transaction
     * @param string $transactionNo
     * @throws \Alhelwany\LaravelEcash\Exceptions\EcashServerErrorException
     * @throws \Alhelwany\LaravelEcash\Exceptions\InvalidConfigurationException
     * @return void
     */
    public function reverse(string $transactionNo): void
    {
        $token = $this->getAccessToken();

        $response = Http::withToken($token)->post(config('ecash.gatewayUrl') . '/api/transaction-managementservice/Public/MerchantTransaction/ReverseTransaction', [
            'TransactionNo' => $transactionNo,
        ]);

        if ($response->failed()) 
            throw new EcashServerErrorException;
    }
}
