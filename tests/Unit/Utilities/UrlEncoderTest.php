<?php

use GeorgeTheNerd\LaravelEcash\Utilities\UrlEncoder;

$encoderObject = new UrlEncoder;

it('encodes URLs', function () use ($encoderObject) {
    expect($encoderObject->encode('https://www.google.com'))->toBe('https%3A%2F%2Fwww.google.com');
});

it('returns null if URL is null', function () use ($encoderObject) {
    expect($encoderObject->encode(null))->toBeNull();
});
