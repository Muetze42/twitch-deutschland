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
