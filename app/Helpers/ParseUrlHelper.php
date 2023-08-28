<?php

namespace App\Helpers;

class ParseUrlHelper
{
    public static function ParseUrl(string $url)
    {
        return parse_url($url, PHP_URL_PATH);
    }
}
