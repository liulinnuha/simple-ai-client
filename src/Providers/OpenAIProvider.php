<?php

namespace Liulinnuha\SimpleAiClient\Providers;

use Liulinnuha\SimpleAiClient\Contracts\Features\Transcription;
use Liulinnuha\SimpleAiClient\DTOs\AiResponse;

class OpenAIProvider extends AbstractProvider implements Transcription
{
    /**
     * Chat with OpenAI.
     *
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

            return new AiResponse(success: true, data: $response);
        } catch (\Throwable $e) {
            return new AiResponse(success: false, error: $e->getMessage());
        }
    }

    /**
     * Embed text.
     *
     * @param array $payload
     * @param array $options
     * @return AiResponse
     */
    public function embed(array $payload, array $options = []): AiResponse
    {
        $payloads = $this->preparePayloadWithDefaultModel($payload, $options);
        try {
            $response = $this->http->post('/embeddings', $payloads)->json();

            return new AiResponse(success: true, data: $response);
        } catch (\Throwable $e) {
            return new AiResponse(success: false, error: $e->getMessage());
        }
    }

    /**
     * Moderate text.
     *
     * @param string $input
     * @param array $options
     * @return AiResponse
     */
    public function moderate(string $input, array $options = []): AiResponse
    {
        $payload = array_merge(['input' => $input], $options);

        return $this->http->post('/moderations', $payload)->json();
    }

    /**
     * Transcribe audio file.
     *
     * @param string $filePath
     * @param array $options
     * @return AiResponse
     */
    public function transcribeAudio(
        string $filePath,
        array $options = [],
    ): AiResponse {
        try {
            $response = $this->http
                ->withToken($this->config['api_key'])
                ->asMultipart()
                ->attach(
                    'file',
                    file_get_contents($filePath),
                    basename($filePath),
                )
                ->post('/audio/transcriptions', $options)
                ->json();

            return new AiResponse(true, $response, null);
        } catch (\Throwable $e) {
            return new AiResponse(false, null, $e->getMessage());
        }
    }
}
