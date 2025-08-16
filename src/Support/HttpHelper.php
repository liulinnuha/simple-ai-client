<?php

namespace Liulinnuha\SimpleAiClient\Support;

use Illuminate\Support\Facades\Http;

class HttpHelper
{
    public static function client(array $config = [])
    {
        $timeout = $config['timeout'] ?? 10;
        $connectTimeout = $config['connect_timeout'] ?? 10;
        $verify = $config['verify'] ?? true;
        $proxy = $config['proxy'] ?? null;
        $headers = $config['headers'] ?? [];
        $retryTimes = $config['retry']['times'] ?? 0;
        $retrySleep = $config['retry']['sleep'] ?? 0;

        $client = Http::timeout($timeout)
            ->connectTimeout($connectTimeout)
            ->withOptions([
                'verify' => $verify,
                'proxy' => $proxy,
            ])
            ->withHeaders($headers)
            ->retry($retryTimes, $retrySleep);

        return $client;
    }
}
