<?php
namespace Liulinnuha\SimpleAiClient\Contracts\Features;

use Liulinnuha\SimpleAiClient\DTOs\AiResponse;

interface Transcription
{
    public function transcribeAudio(
        string $filePath,
        array $options = [],
    ): AiResponse;
}
