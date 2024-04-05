<?php

namespace MhdGhaithAlhelwany\LaravelEcash\Utilities;

class UrlEncoder
{
    /**
     * Encodes Url using urlencode, returns null if the $url is null
     *
     * @return void
     */
    public function encode(?string $url): ?string
    {
        return is_null($url) ? null : urlencode($url);
    }
}
