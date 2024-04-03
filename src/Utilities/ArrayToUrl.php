<?php

namespace GeorgeTheNerd\LaravelEcash\Utilities;

class ArrayToUrl
{
    /**
     * Generates a URL from a base Url & args, ignores nulls in the array
     */
    public function generate(string $baseUrl, array $args): string
    {
        $url = $baseUrl;
        foreach ($args as $arg) {
            if (! is_null($arg)) {
                $url .= '/'.$arg;
            }
        }

        return $url;
    }
}
