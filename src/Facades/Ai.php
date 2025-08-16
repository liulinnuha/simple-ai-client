<?php

namespace Liulinnuha\SimpleAiClient\Facades;

use Illuminate\Support\Facades\Facade;

class Ai extends Facade
{
    /**
     * @method static \Liulinnuha\SimpleAiClient\Dto\AiResponse chat(array $payload, array $options = [])
     * @method static \Liulinnuha\SimpleAiClient\Dto\AiResponse embed(array $payload, array $options = [])
     * @method static \Liulinnuha\SimpleAiClient\Dto\AiResponse moderate(string $input, array $options = [])
     *
     *
     * @method static \Liulinnuha\SimpleAiClient\Dto\AiResponse transcribeAudio(string $filePath, array $options = [])
     */
    protected static function getFacadeAccessor()
    {
        return 'ai';
    }
}
