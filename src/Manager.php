<?php

namespace Liulinnuha\SimpleAiClient;

use Exception;
use Liulinnuha\SimpleAiClient\Support\ProviderFactory;
use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * Get the default driver name from config.
     */
    public function getDefaultDriver()
    {
        return $this->config['ai.default'] ?? null;
    }

    /**
     * Create provider instance.
     */
    public function createDriver($driver)
    {
        $providers = $this->config['ai.providers'] ?? [];

        if (!isset($providers[$driver])) {
            throw new Exception("AI provider [{$driver}] is not configured.");
        }

        $providerConfig = $providers[$driver];
        $providerConfig['http'] = $this->config['ai.http'] ?? [];

        return ProviderFactory::make($driver, $providerConfig);
    }
}
