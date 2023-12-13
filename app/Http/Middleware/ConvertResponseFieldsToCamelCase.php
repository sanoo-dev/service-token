<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Str;

class ConvertResponseFieldsToCamelCase
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $content = $response->getContent();

        try {
            $array = @json_decode($content, true);
            $replaced = $this->convertToCamelCase($array);

            if ($response instanceof JsonResponse) {
                return $response->setData($replaced);
            } else {
                $response->setContent(@json_encode($replaced));
            }
        } catch (Exception $e) {
            // you can log an error here if you want
        }
        return $response;
    }

    private function convertToCamelCase($array): array
    {
        $replaced = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $replaced[Str::camel($key)] = $this->convertToCamelCase($value);
            } else {
                $replaced[Str::camel($key)] = $value;
            }
        }
        return $replaced;
    }
}