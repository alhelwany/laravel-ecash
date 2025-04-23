<?php

use Alhelwany\LaravelEcash\Utilities\ArrayToUrl;

$arrayToUrlObject = new ArrayToUrl;

it('can generate URL from Array', function () use ($arrayToUrlObject) {
    $array = [
        'hi' => 'param1',
        null,
        'hii' => 'param2',
        'num1' => 69.69,
        null,
        'num2' => 420,
    ];
    $baseUrl = 'https://www.google.com';
    $expectedUrl = 'https://www.google.com?hi=param1&hii=param2&num1=69.69&num2=420';
    expect($arrayToUrlObject->generate($baseUrl, $array))->toBe($expectedUrl);
});
