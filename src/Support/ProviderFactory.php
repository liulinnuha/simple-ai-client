<?php

namespace Liulinnuha\SimpleAiClient\Support;

class ProviderFactory
{
    /**
     * Create provider instance based on name and configuration.
     *
     * @param string $name provider name, eg 'openai', 'gemini', 'deepseek'
     * @param array $config
     * @return \Liulinnuha\SimpleAiClient\Contracts\AiProviderInterface
     *
     * @throws AiSdkException
     */
    public static function make(string $name, array $config)
    {
        $providersMap = [
            'openai' =>
                \Liulinnuha\SimpleAiClient\Providers\OpenAIProvider::class,
            'gemini' =>
                \Liulinnuha\SimpleAiClient\Providers\GeminiProvider::class,
            'deepseek' =>
                \Liulinnuha\SimpleAiClient\Providers\DeepSeekProvider::class,
        ];

        $name = strtolower($name);

        if (!isset($providersMap[$name])) {
            throw new \InvalidArgumentException(
                "AI Provider '{$name}' not supported.",
            );
        }

        $class = $providersMap[$name];

        return new $class($config);
    }
}
