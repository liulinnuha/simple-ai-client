<?php

namespace Liulinnuha\SimpleAiClient;

use Exception;
use Liulinnuha\SimpleAiClient\Contracts\AiProviderInterface;
use Liulinnuha\SimpleAiClient\DTOs\AiResponse;
use Liulinnuha\SimpleAiClient\Exceptions\FeatureNotSupportedException;

class Client
{
    protected Manager $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get the active provider instance.
     */
    public function provider(?string $name = null): AiProviderInterface
    {
        return $this->manager->driver($name);
    }

    public function chat(array $payload, array $options = []): AiResponse
    {
        return $this->provider()->chat($payload, $options);
    }

    public function embed(array $payload, array $options = []): AiResponse
    {
        $provider = $this->provider();

        if (!method_exists($provider, 'embed')) {
            throw new Exception('Embed not supported by this provider.');
        }

        return $provider->embed($payload, $options);
    }

    public function moderate(string $input, array $options = []): AiResponse
    {
        $provider = $this->provider();

        if (!method_exists($provider, 'moderate')) {
            throw new Exception('Moderation not supported by this provider.');
        }

        return $provider->moderate($input, $options);
    }

    public function supports(
        string $featureInterface,
        ?string $providerName = null,
    ): bool {
        return is_a($this->provider($providerName), $featureInterface);
    }

    public function as(string $classOrInterface, ?string $providerName = null)
    {
        $p = $this->provider($providerName);
        if (!$p instanceof $classOrInterface) {
            throw FeatureNotSupportedException::for(
                $classOrInterface,
                get_class($p),
            );
        }

        return $p;
    }

    public function __call(string $method, array $arguments)
    {
        $p = $this->provider();

        if (!method_exists($p, $method)) {
            throw new \BadMethodCallException(
                "Method {$method} does not on " .
                    get_class($this) .
                    'or provider ' .
                    get_class($p) .
                    '.',
            );
        }

        return $p->{$method}($arguments);
    }
}
