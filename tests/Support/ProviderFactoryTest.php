<?php

namespace Liulinnuha\SimpleAiClient\Tests;

use Liulinnuha\SimpleAiClient\Support\ProviderFactory;
use Liulinnuha\SimpleAiClient\Providers\OpenAIProvider;
use Orchestra\Testbench\TestCase;

class ProviderFactoryTest extends TestCase
{
    public function testMakeOpenAIProvider(): void
    {
        $config = [
            'api_key' => 'test-key',
            'base_url' => 'https://api.openai.com/v1',
            'http' => [],
        ];

        $provider = ProviderFactory::make('openai', $config);

        $this->assertInstanceOf(OpenAIProvider::class, $provider);
    }

    public function testMakeInvalidProviderThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        ProviderFactory::make('invalid-provider', []);
    }
}
