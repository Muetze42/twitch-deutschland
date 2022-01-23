<?php

if (!function_exists('lastAnd')) {
    /**
     * Replace the last comma in a list with `and`
     *
     * @param string $string
     * @param string $word
     * @param string $glue
     * @return string
     */
    function lastAnd(string $string, string $word = 'und', string $glue = ','): string
    {
        if (!preg_match('/,/', $string)) {
            return $string;
        }
        return substr_replace($string, ' '.$word, strrpos($string, $glue), 1);
    }
}


if (!function_exists('errorImage')) {
    /**
     * @param int $errorCode
     * @return string
     */
    function errorImage(int $errorCode): string
    {
        $errorImages = [
            '401' => '403.svg',
            '403' => '403.svg',
            '404' => '404.svg',
            '500' => '503.svg',
        ];

        return $errorImages[$errorCode] ?? '404.svg';
    }
}
