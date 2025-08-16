<?php

namespace Liulinnuha\SimpleAiClient\Providers;

use Liulinnuha\SimpleAiClient\DTOs\AiResponse;

class GeminiProvider extends AbstractProvider
{
    public function chat(array $payload, array $options = []): AiResponse
    {
        // TODO: implement Gemini API calls
        throw new \RuntimeException('Gemini provider not implemented yet.');
    }
}
