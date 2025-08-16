<?php

return [
    /*
    |--------------------------------------------------------------------------
    | AI Provider
    |--------------------------------------------------------------------------
    |
    | Default provider with which the SDK will interact.
    |
    */
    'default' => env('AI_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Request Settings
    |--------------------------------------------------------------------------
    |
    | All configuration related to HTTP client (Guzzle) is placed here.
    | Can be customized according to needs, and will be passed to Client.
    |
    */
    'http' => [
        'timeout' => 30,
        'connect_timeout' => 10,
        'verify' => true, // SSL verify
        'proxy' => null,
        'headers' => [
            'User-Agent' =>
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36',
            'Accept' => 'application/json',
        ],
        'retry' => [
            'times' => 3,
            'sleep' => 100, // ms
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    */
    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
            'default_model' => 'gpt-3.5-turbo',
        ],
        'gemini' => [
            'api_key' => env('GEMINI_API_KEY'),
            'base_url' => env(
                'GEMINI_BASE_URL',
                'https://generativelanguage.googleapis.com/v1beta/',
            ),
        ],
        'deepseek' => [
            'api_key' => env('DEEPSEEK_API_KEY'),
            'base_url' => env(
                'DEEPSEEK_BASE_URL',
                'https://api.deepseek.com/v1/',
            ),
            'default_model' => 'deepseek-chat',
        ],
    ],
];
