<?php

namespace Liulinnuha\SimpleAiClient\Providers;

use Liulinnuha\SimpleAiClient\Contracts\Features\GetBalance;
use Liulinnuha\SimpleAiClient\DTOs\AiResponse;

class DeepSeekProvider extends AbstractProvider implements GetBalance
{
    /**
     * @param array $payload
     * @param array $options
     * @return AiResponse
     */
    public function chat(array $payload, array $options = []): AiResponse
    {
        $payloads = $this->preparePayloadWithDefaultModel($payload, $options);

        try {
            $response = $this->http
                ->post('/chat/completions', $payloads)
                ->json();

            return new AiResponse(true, $response, null);
        } catch (\Throwable $e) {
            return new AiResponse(false, null, $e->getMessage());
        }
    }

    /**
     * Get Balance
     * @return AiResponse
     */
    public function getBalance(): AiResponse
    {
        try {
            $response = $this->http->get('/user/balance')->json();

            return new AiResponse(true, $response, null);
        } catch (\Throwable $e) {
            return new AiResponse(false, null, $e->getMessage());
        }
    }
}
