<?php

namespace Liulinnuha\SimpleAiClient\Support;

class MessageBuilder
{
    public static function normalizeChatPayload(
        array $payload,
        array $providerConfig = [],
    ): array {
        $messages = $payload['messages'] ?? [];

        if (is_string($messages)) {
            $messages = [['role' => 'user', 'content' => $messages]];
        }

        $model =
            $payload['model'] ??
            ($providerConfig['model'] ??
                ($providerConfig['default_model'] ?? null));

        $body = [
            'model' => $model ?: 'gpt-3.5-turbo',
            'messages' => $messages,
        ];

        foreach (['temperature', 'max_tokens', 'top_p', 'n', 'stop'] as $k) {
            if (isset($payload[$k])) {
                $body[$k] = $payload[$k];
            }
        }

        return $body;
    }
}
