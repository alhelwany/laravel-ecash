<?php

namespace GeorgeTheNerd\LaravelEcash\Utilities;

class UrlEncoder
{
    public function encode(?string $url)
    {
        return is_null($url) ? null : urlencode($url);
    }
}
