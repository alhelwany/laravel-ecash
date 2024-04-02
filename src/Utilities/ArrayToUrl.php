<?php

namespace GeorgeTheNerd\LaravelEcash\Utilities;

class ArrayToUrl
{
    public function generate(string $baseUrl, array $args)
    {
        $url = $baseUrl;
        foreach ($args as $arg)
            if (!is_null($arg))
                $url .= '/' . $arg;
        return $url;
    }
}
