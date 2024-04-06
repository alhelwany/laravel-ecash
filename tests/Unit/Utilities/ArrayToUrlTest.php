<?php

use Organon\LaravelEcash\Utilities\ArrayToUrl;

$arrayToUrlObject = new ArrayToUrl;

it('can generate URL from Array', function () use ($arrayToUrlObject) {
    $array = [
        'param1',
        null,
        'param2',
        69.69,
        null,
        420,
    ];
    $baseUrl = 'https://www.google.com';
    $expectedUrl = 'https://www.google.com/param1/param2/69.69/420';
    expect($arrayToUrlObject->generate($baseUrl, $array))->toBe($expectedUrl);
});
