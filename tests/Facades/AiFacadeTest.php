<?php
namespace Liulinnuha\SimpleAiClient\Tests\Facades;

use Liulinnuha\SimpleAiClient\Facades\Ai;
use Liulinnuha\SimpleAiClient\Manager;
use Liulinnuha\SimpleAiClient\Client;
use Orchestra\Testbench\TestCase;

class AiFacadeTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [\Liulinnuha\SimpleAiClient\AiServiceProvider::class];
    }

    public function testFacadeResolvesClient()
    {
        $client = $this->app->make('ai');
        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals($client, Ai::getFacadeRoot());
    }
}
