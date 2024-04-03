<?php

namespace GeorgeTheNerd\LaravelEcash\Utilities;

class VerificationCodeGenerator
{

    private string $merchantId;
    private string $merchantSecret;

    public function __construct(string $merchantId, string $merchantSecret)
    {
        $this->merchantId = $merchantId;
        $this->merchantSecret = $merchantSecret;
    }

    /**
     * Generates Verification Token
     *
     * @param float $amount
     * @param string $orderRef
     * @return string
     */
    public function generate(float $amount, string $orderRef): string
    {
        return strtoupper(md5($this->merchantId . $this->merchantSecret . $amount . $orderRef));
    }
}
