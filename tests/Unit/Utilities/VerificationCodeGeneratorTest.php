<?php

use Alhelwany\LaravelEcash\Utilities\VerificationCodeGenerator;

$verificationCodeGenerator = new VerificationCodeGenerator('12345', '54321');

it('generates verification code', function () use ($verificationCodeGenerator) {
    $code = $verificationCodeGenerator->generate(100, 10);
    expect($code)->toBe('AF152435F70B7B5EA5466BA3DBE2DF99');
});
