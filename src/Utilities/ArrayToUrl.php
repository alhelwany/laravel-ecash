<?php

namespace GeorgeTheNerd\LaravelEcash\Utilities;

class ArrayToUrl
{
    /**
     * Generates a URL from a base Url & args, ignores nulls in the array
     *
     * @param string $baseUrl
     * @param array $args
     * @return string
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
