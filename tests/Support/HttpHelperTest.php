<?php

namespace Liulinnuha\SimpleAiClient\Tests\Support;

use Illuminate\Support\Facades\Http;
use Liulinnuha\SimpleAiClient\Support\HttpHelper;
use Orchestra\Testbench\TestCase;

class HttpHelperTest extends TestCase
{
    public function testClientReturnsHttpPendingRequest(): void
    {
        $config = [
            'http' => [
                'timeout' => 5,
                'connect_timeout' => 3,
                'verify' => false,
                'proxy' => 'http://proxy.test',
                'headers' => ['X-Test' => 'test-header'],
                'retry' => ['times' => 2, 'sleep' => 50],
            ],
        ];

        $client = HttpHelper::client($config);

        $this->assertInstanceOf(
            \Illuminate\Http\Client\PendingRequest::class,
            $client,
        );
    }

    public function testClientHandlesEmptyConfigGracefully(): void
    {
        $client = HttpHelper::client([]);

        $this->assertInstanceOf(
            \Illuminate\Http\Client\PendingRequest::class,
            $client,
        );
    }
}
