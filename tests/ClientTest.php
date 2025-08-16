<?php

namespace Liulinnuha\SimpleAiClient\Tests;

use Liulinnuha\SimpleAiClient\Client;
use Liulinnuha\SimpleAiClient\Manager;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Http;

class ClientTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [\Liulinnuha\SimpleAiClient\AiServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('ai.default', 'openai');
        $app['config']->set('ai.providers.openai', [
            'default_model' => 'gpt-3.5',
            'api_key' => 'test-key',
            'base_url' => 'https://api.openai.com/v1',
        ]);
        $app['config']->set('ai.http', [
            'timeout' => 5,
            'connect_timeout' => 5,
            'verify' => true,
            'proxy' => null,
            'headers' => [
                'User-Agent' => 'Testing-Agent',
            ],
            'retry' => [
                'times' => 1,
                'sleep' => 100,
            ],
        ]);
    }

    public function testChatCallsOpenAIProvider(): void
    {
        Http::fake([
            'api.openai.com/v1/chat/completions' => Http::response(
                [
                    'id' => 'chatcmpl-123',
                    'choices' => [
                        ['message' => ['content' => 'Hello from OpenAI!']],
                    ],
                ],
                200,
            ),
        ]);

        $manager = $this->app->make(Manager::class);
        $client = new Client($manager);

        $response = $client->chat([
            'messages' => [['role' => 'user', 'content' => 'Halo!']],
        ]);

        // Response is an AiResponse object, not an array
        $this->assertTrue($response->isSuccess());
        $this->assertEquals('chatcmpl-123', $response->data['id']);
        $this->assertEquals(
            'Hello from OpenAI!',
            $response->data['choices'][0]['message']['content'],
        );
    }

    // public function testEmbedThrowsExceptionIfNotSupported(): void
    // {
    //     $this->expectException(\BadMethodCallException::class);

    //     $manager = $this->app->make(Manager::class);
    //     $client = new Client($manager);

    //     // Assuming default provider doesn't support embed
    //     $client->embed(['input' => 'test']);
    // }
}
