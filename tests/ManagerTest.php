<?php

namespace Liulinnuha\SimpleAiClient\Tests;

use Exception;
use Liulinnuha\SimpleAiClient\Manager;
use Orchestra\Testbench\TestCase;

class ManagerTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [\Liulinnuha\SimpleAiClient\AiServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('ai.default', 'openai');
        $app['config']->set('ai.providers.openai', [
            'api_key' => 'test-key',
            'base_url' => 'https://api.openai.com/v1',
        ]);
        $app['config']->set('ai.http', []);
    }

    public function testGetDefaultDriver(): void
    {
        $manager = $this->app->make(Manager::class);
        $this->assertEquals('openai', $manager->getDefaultDriver());
    }

    public function testCreateDriverThrowsExceptionWhenProviderNotConfigured(): void
    {
        $this->expectException(Exception::class);

        $manager = $this->app->make(Manager::class);
        $manager->driver('nonexistent');
    }
}
