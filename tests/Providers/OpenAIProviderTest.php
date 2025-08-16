<?php
namespace Liulinnuha\SimpleAiClient\Tests\Providers;

use Illuminate\Support\Facades\Http;
use Liulinnuha\SimpleAiClient\Providers\OpenAIProvider;
use Orchestra\Testbench\TestCase;

class OpenAIProviderTest extends TestCase
{
    public function testChatReturnsResponse()
    {
        Http::fake([
            'https://api.openai.com/v1/chat/completions' => Http::response(
                ['foo' => 'bar'],
                200,
            ),
        ]);

        $config = [
            'api_key' => 'testkey',
            'base_url' => 'https://api.openai.com/v1',
            'default_model' => 'gpt-3.5',
            'http' => [],
        ];

        $provider = new OpenAIProvider($config);

        $response = $provider->chat([
            'messages' => [['role' => 'user', 'content' => 'Hello']],
        ]);

        $this->assertTrue($response->isSuccess());
    }
}
