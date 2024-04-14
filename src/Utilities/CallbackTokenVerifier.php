<?php

namespace Organon\LaravelEcash\Utilities;

class CallbackTokenVerifier
{
    private string $merchantId;

    private string $merchantSecret;

    /**
     * @param string $merchantId
     * @param string $merchantSecret
     */
    public function __construct(string $merchantId, string $merchantSecret)
    {
        $this->merchantId = $merchantId;
        $this->merchantSecret = $merchantSecret;
    }

    /**
     * Verifies Token by calculating MD5 Hash based on the documentation of ECash
     *
     * @param string $token
     * @param string $transactionNo
     * @param string $amount
     * @param integer $orderRef
     * @return boolean
     */
    public function verify(string $token, string $transactionNo, string $amount, int $orderRef): bool
    {
        return strtoupper($token) == strtoupper(md5($this->merchantId . $this->merchantSecret . $transactionNo . $amount . $orderRef));
    }
}
