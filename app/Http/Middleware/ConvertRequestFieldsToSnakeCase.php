<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Str;

class ConvertRequestFieldsToSnakeCase
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
        $replaced = $this->convertToSnakeCase($request->all());
        $request->replace($replaced);

        return $next($request);
    }

    private function convertToSnakeCase($array): array
    {
        $replaced = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $replaced[Str::snake($key)] = $this->convertToSnakeCase($value);
            } else {
                $replaced[Str::snake($key)] = $value;
            }
        }
        return $replaced;
    }
}