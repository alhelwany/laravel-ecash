<?php

namespace Alhelwany\LaravelEcash\Utilities;

class ArrayToUrl
{
    /**
     * enerates a URL from a base Url & args
     *
     * @param string $baseUrl
     * @param array $args
     * @return string
     */
    public function generate(string $baseUrl, array $args): string
    {
        return $baseUrl . '?' . http_build_query($args);
    }
}
