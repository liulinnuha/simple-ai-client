<?php

namespace Liulinnuha\SimpleAiClient\Providers;

use Liulinnuha\SimpleAiClient\Contracts\AiProviderInterface;
use Liulinnuha\SimpleAiClient\DTOs\AiResponse;
use Liulinnuha\SimpleAiClient\Support\HttpHelper;

abstract class AbstractProvider implements AiProviderInterface
{
    protected array $config = [];
    protected $http;
    protected ?string $defaultModel = null;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->http = $this->setupHttpClient($config);
        $this->defaultModel = $config['default_model'] ?? null;
    }

    /**
     * Set up the HTTP client with proper authentication
     * This can be overridden by specific providers to implement different auth methods
     *
     * @param array $config
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function setupHttpClient(array $config)
    {
        return HttpHelper::client($config['http'] ?? [])
            ->withToken($config['api_key'])
            ->baseUrl(rtrim($config['base_url'], '/'));
    }

    /**
     * Chat not implemented for this provider.
     */
    abstract public function chat(
        array $payload,
        array $options = [],
    ): AiResponse;

    /**
     * Embedding not implemented for this provider.
     */
    public function embed(array $payload, array $options = []): AiResponse
    {
        throw new \BadMethodCallException(
            'Embedding not implemented for this provider.',
        );
    }

    /**
     * Moderation not implemented for this provider.
     * @param string $input
     * @param array $options
     * @return AiResponse
     */
    public function moderate(string $input, array $options = []): AiResponse
    {
        throw new \BadMethodCallException(
            'Moderation not implemented for this provider.',
        );
    }

    /**
     * Prepare payload with default model.
     * @param array $payload
     * @param array $options
     * @return array
     */
    protected function preparePayloadWithDefaultModel(
        array $payload,
        array $options = [],
    ): array {
        if (!isset($payload['model'])) {
            if (!$this->defaultModel) {
                throw new \InvalidArgumentException(
                    'Model must be specified in payload or config.',
                );
            }
            $payload['model'] = $this->defaultModel;
        }

        return $this->mergePayloadWithOptions($payload, $options);
    }

    /**
     * Return default options for the provider,
     * including default model and maybe other shared options.
     */
    protected function getDefaultOptions(): array
    {
        return [
            'model' => $this->defaultModel,
            'max_tokens' => 256,
            'temperature' => 0.7,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ];
    }

    /**
     * Merge options with default options.
     * @param array $options
     * @return array
     */
    protected function mergeOptions(array $options = []): array
    {
        return array_merge($this->getDefaultOptions(), $options);
    }

    /**
     * Merge payload with options.
     */
    protected function mergePayloadWithOptions(
        array $payload,
        array $options = [],
    ): array {
        $mergedOptions = $this->mergeOptions($options);

        return array_merge($mergedOptions, $payload);
    }
}
