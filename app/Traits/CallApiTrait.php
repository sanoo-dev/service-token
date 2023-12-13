<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Log\LogLevel;

trait CallApiTrait
{
//    public function callWithGuzzle(string $method, string $url, array $parameters = [], $headers = []): mixed
//    {
//        $body = match (strtoupper($method)) {
//            'POST', 'PUT', 'PATCH' => ['form_params' => $parameters],
//            default => ['query' => $parameters],
//        };
//        $client = new Client();
//
//        try {
//            $response = $client->request($method, $url, $body + ['headers' => $headers]);
//            Log::info($response->getBody()->getContents());
//            return array_merge(['success' => true], (array)@json_decode($response->getBody(), true));
//        } catch (ClientException|ServerException $e) {
//            Log::warning($e->getResponse()->getBody()->getContents());
//            return array_merge(['success' => false], (array)@json_decode((string)$e->getResponse()->getBody(), true));
//        } catch (GuzzleException|Exception $e) {
//            Log::error($e->getMessage());
//            return [
//                'success' => false
//            ];
//        }
//    }

    public function call(string $method, string $url, array $parameters = [], $headers = []): array
    {
        $body = match (strtoupper($method)) {
            'POST', 'PUT', 'PATCH' => ['form_params' => $parameters],
            default => ['query' => $parameters],
        };
        Log::info("Request params: " . json_encode($parameters));
        Log::info("Headers: " . json_encode($headers));
        try {
            $response = Http::withHeaders($headers)->send($method, $url, $body);
            if ($response->ok()) {
                $level = LogLevel::INFO;
                $result = array_merge(['success' => true], (array)@json_decode($response->body(), true));
            } elseif ($response->failed()) {
                $level = LogLevel::WARNING;
                $result = array_merge(['success' => false], (array)@json_decode($response->body(), true));
            } else {
                $level = LogLevel::ERROR;
                $result = ['success' => false, 'message' => __('base.error.other')];
            }
            $response->close();
        } catch (Exception $e) {
            $level = LogLevel::ERROR;
            $result = ['success' => false, 'message' => __('base.error.other'), 'exception' => $e->getMessage()];
        }
        Log::log($level, "$url - " . json_encode($result));
        return $result;
    }
}
