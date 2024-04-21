<?php

namespace Alhelwany\LaravelEcash\Utilities;

class UrlEncoder
{
    /**
     * Encodes Url using urlencode, returns null if the $url is null
     *
     * @param string|null $url
     * @return string|null
     */
    public function encode(?string $url): ?string
    {
        return is_null($url) ? null : urlencode($url);
    }

    /**
     * Decodes URL using urldecode, returns null if $url is null
     *
     * @param string|null $url
     * @return string|null
     */
    public function decode(?string $url): ?string
    {
        return is_null($url) ? null : urldecode($url);
    }
}
