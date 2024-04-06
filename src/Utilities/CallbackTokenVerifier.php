<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Utilities;

class CallbackTokenVerifier
{
    private string $merchantId;

    private string $merchantSecret;

    public function __construct(string $merchantId, string $merchantSecret)
    {
        $this->merchantId = $merchantId;
        $this->merchantSecret = $merchantSecret;
    }

    public function verify(string $token, string $transactionNo, float $amount, int $orderRef): string
    {
        return strtoupper($token) == strtoupper(md5($this->merchantId . $this->merchantSecret . $transactionNo . $amount . $orderRef));
    }
}
