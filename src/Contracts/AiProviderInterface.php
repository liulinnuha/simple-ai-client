<?php

namespace Liulinnuha\SimpleAiClient\Contracts;

use Liulinnuha\SimpleAiClient\DTOs\AiResponse;

interface AiProviderInterface
{
    /**
     * Send a chat request to the provider.
     * @param array $payload
     * @param array $options
     * @return AiResponse
     */
    public function chat(array $payload, array $options = []): AiResponse;

    /**
     * Optional: create embeddings
     * @param array $payload
     * @return AiResponse
     */
    public function embed(array $payload, array $options = []): AiResponse;

    /**
     * Optional: moderate content
     * @param string $input
     * @param array $options
     * @return AiResponse
     */
    public function moderate(string $input, array $options = []): AiResponse;
}
