<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompressHTML
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        if($this->IsResponseObject($response) && $this->IsHtmlResponse($response)) {
            $source = $response->getContent();
            $response->setContent($this->compressHTML($source));
        }

        return $response;
    }

    /**
     * @param $response
     * @return bool
     */
    protected function IsResponseObject($response): bool
    {
        return $response instanceof Response;
    }

    /**
     * @param Response $response
     * @return bool
     */
    protected function IsHtmlResponse(Response $response): bool
    {
        $type = $response->headers->get('Content-Type');

        return strtolower(strtok($type, ';')) === 'text/html';
    }

    /**
     * @param string $buffer
     * @return string
     */
    protected function compressHTML(string $buffer): string
    {
        preg_match_all('#<textarea.*>.*</textarea>#Uis', $buffer, $foundTxt);
        preg_match_all('#<pre.*>.*</pre>#Uis', $buffer, $foundPre);

        $buffer = str_replace($foundTxt[0], array_map(function ($elm) {
            return '<textarea>'.$elm.'</textarea>';
        }, array_keys($foundTxt[0])), $buffer);
        $buffer = str_replace($foundPre[0], array_map(function ($elm) {
            return '<pre>'.$elm.'</pre>';
        }, array_keys($foundPre[0])), $buffer);

        // your stuff
        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
            '/[^\S ]+\</s',  // strip whitespaces before tags, except space
            '/(\s)+/s',      // shorten multiple whitespace sequences
        );

        $replace = array(
            '>',
            '<',
            '\\1',
        );

        $buffer = preg_replace($search, $replace, $buffer);

        // Replacing back with content
        $buffer = str_replace(array_map(function ($elm) {
            return '<textarea>'.$elm.'</textarea>';
        }, array_keys($foundTxt[0])), $foundTxt[0], $buffer);
        $buffer = str_replace(array_map(function ($elm) {
            return '<pre>'.$elm.'</pre>';
        }, array_keys($foundPre[0])), $foundPre[0], $buffer);

        $buffer = str_replace('> <', '><', $buffer);
        $buffer = str_replace('<script type="text/javascript">', '<script>', $buffer);

        return preg_replace('/(<a href="(http|https):(?!\/\/(?:www\.)?daysndaze\.(test|net))[^"]+")>/is', '\\1 target="_blank">', $buffer);
    }
}
