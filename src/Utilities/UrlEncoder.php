<?php

namespace GeorgeTheNerd\LaravelEcash\Utilities;

class UrlEncoder
{
    /**
     * Encodes Url using urlencode, returns null if the $url is null
     *
     * @param string|null $url
     * @return void
     */
    public function encode(string|null $url): string|null
    {
        return is_null($url) ? null : urlencode($url);
    }
}
