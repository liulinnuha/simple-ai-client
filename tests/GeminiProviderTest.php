<?php

namespace Liulinnuha\SimpleAiClient\Tests;

use Liulinnuha\SimpleAiClient\Client;
use Liulinnuha\SimpleAiClient\Manager;
use Liulinnuha\SimpleAiClient\Providers\GeminiProvider;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Http;

class GeminiProviderTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [\Liulinnuha\SimpleAiClient\AiServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('ai.default', 'gemini');
        $app['config']->set('ai.providers.gemini', [
            'default_model' => 'gemini-pro',
            'embedding_model' => 'gemini-embedding-001',
            'api_key' => 'test-gemini-key',
            'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
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

    public function testGeminiProviderUsesCorrectAuthenticationHeader(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(
                [
                    'candidates' => [
                        [
                            'content' => [
                                'parts' => [['text' => 'Hello from Gemini!']],
                                'role' => 'model',
                            ],
                        ],
                    ],
                ],
                200,
            ),
        ]);

        $manager = $this->app->make(Manager::class);
        $client = new Client($manager);

        $response = $client->chat([
            'messages' => [['role' => 'user', 'content' => 'Hello!']],
        ]);

        // Verify the request had the correct headers
        Http::assertSent(function ($request) {
            return $request->hasHeader('x-goog-api-key', 'test-gemini-key') &&
                $request->url() ===
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
        });

        $this->assertTrue($response->isSuccess());
        $this->assertEquals(
            'Hello from Gemini!',
            $response->data['candidates'][0]['content']['parts'][0]['text'],
        );
    }

    public function testGeminiEmbeddingUsesCorrectFormat(): void
    {
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response(
                [
                    'embeddings' => [
                        [
                            'values' => [0.1, 0.2, 0.3, 0.4],
                        ],
                    ],
                ],
                200,
            ),
        ]);

        $manager = $this->app->make(Manager::class);
        $provider = new GeminiProvider([
            'api_key' => 'test-gemini-key',
            'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
            'default_model' => 'gemini-pro',
            'embedding_model' => 'gemini-embedding-001',
            'http' => [],
        ]);

        $response = $provider->embed([
            'input' => 'Test text for embedding',
            'model' => 'gemini-embedding-001',
        ]);

        // Verify the request was formatted correctly
        Http::assertSent(function ($request) {
            $body = json_decode($request->body(), true);
            return $request->hasHeader('x-goog-api-key', 'test-gemini-key') &&
                $request->url() ===
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-embedding-001:embedContent' &&
                isset($body['content']) &&
                is_array($body['content']) &&
                isset($body['content'][0]['parts']) &&
                isset($body['content'][0]['parts'][0]['text']) &&
                $body['content'][0]['parts'][0]['text'] ===
                    'Test text for embedding';
        });

        $this->assertTrue($response->isSuccess());
        $this->assertEquals(
            [0.1, 0.2, 0.3, 0.4],
            $response->data['embeddings'][0]['values'],
        );
    }
}
