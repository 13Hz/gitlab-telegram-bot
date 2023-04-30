<?php

namespace App\Traits;

trait ParserTraits
{
    public function getNormalizedUrl(string $url): string|null
    {
        $parsedUrl = null;

        if (preg_match('/^http[s]?:\/\/\S+\.\S+?\/\S+?\/\S+?$/', $url)) {
            $parsedUrl = rtrim(trim($url), '/');
        }

        return $parsedUrl;
    }
}
