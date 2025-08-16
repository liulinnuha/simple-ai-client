<?php

namespace Liulinnuha\SimpleAiClient\Providers;

use Liulinnuha\SimpleAiClient\DTOs\AiResponse;
use Liulinnuha\SimpleAiClient\Support\HttpHelper;

class GeminiProvider extends AbstractProvider
{
    /**
     * Set up the HTTP client with query parameter authentication for Gemini
     *
     * @param array $config
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function setupHttpClient(array $config)
    {
        // Gemini uses x-goog-api-key header instead of Authorization Bearer token
        return HttpHelper::client($config['http'] ?? [])
            ->withHeaders(['x-goog-api-key' => $config['api_key']])
            ->baseUrl(rtrim($config['base_url'], '/'));
    }

    /**
     * Chat with Gemini API
     *
     * @param array $payload
     * @param array $options
     * @return AiResponse
     */
    public function chat(array $payload, array $options = []): AiResponse
    {
        try {
            $processedPayload = $this->preparePayloadWithDefaultModel($payload);

            $response = $this->http
                ->post(
                    '/models/' . $this->defaultModel . ':generateContent',
                    $processedPayload,
                )
                ->json();

            return new AiResponse(success: true, data: $response);
        } catch (\Throwable $e) {
            return new AiResponse(success: false, error: $e->getMessage());
        }
    }

    /**
     * Embedding not implemented for Gemini yet
     */
    public function embed(array $payload, array $options = []): AiResponse
    {
        try {
            // Format content for Gemini embedding format
            $content = [];

            // Handle single input or array of inputs
            $inputs = is_array($payload['input'])
                ? $payload['input']
                : [$payload['input']];

            foreach ($inputs as $input) {
                $content[] = [
                    'parts' => [['text' => $input]],
                ];
            }
            $modelName = $this->config['embedding_model'];

            // Prepare embedding payload in Gemini format
            $processedPayload = [
                'model' => 'models/' . $modelName,
                'contents' => $content,
            ];

            $response = $this->http
                ->post(
                    '/models/' . $modelName . ':embedContent',
                    $processedPayload,
                )
                ->json();

            return new AiResponse(success: true, data: $response);
        } catch (\Throwable $e) {
            return new AiResponse(success: false, error: $e->getMessage());
        }
    }

    /**
     * Override preparePayloadWithDefaultModel to transform messages from OpenAI format to Gemini format if needed
     *
     * @param array $payload
     * @param array $options
     * @return array
     */
    protected function preparePayloadWithDefaultModel(
        array $payload,
        array $options = [],
    ): array {
        // If the payload is already in Gemini format, return it as is
        if (!isset($payload['messages'])) {
            return $payload;
        }

        // Transform from OpenAI-style messages to Gemini format
        $contents = [];
        foreach ($payload['messages'] as $message) {
            // Map OpenAI roles to Gemini roles
            $role = $this->mapRole($message['role']);

            $content = ['role' => $role];

            // Add the message content
            if (is_string($message['content'])) {
                $content['parts'] = [['text' => $message['content']]];
            } else {
                // Handle complex content like images if needed
                $content['parts'] = $message['content'];
            }

            $contents[] = $content;
        }

        $generationConfig = $this->mergePayloadWithOptions($payload, $options);
        unset($generationConfig['messages']);
        unset($generationConfig['model']);

        return [
            'contents' => $contents,
            'generationConfig' => $generationConfig,
        ];
    }

    /**
     * Map OpenAI role to Gemini role
     *
     * @param string $openAiRole
     * @return string
     */
    protected function mapRole(string $openAiRole): string
    {
        return match ($openAiRole) {
            'system', 'assistant' => 'model',
            'user' => 'user',
            default => 'user',
        };
    }

    /**
     * Get default options specific to Gemini
     */
    protected function getDefaultOptions(): array
    {
        return [
            'model' => $this->defaultModel,
            'temperature' => 0.7,
            'top_p' => 1.0,
            'top_k' => 40,
        ];
    }
}
