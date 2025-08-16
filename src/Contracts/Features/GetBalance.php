<?php

namespace Liulinnuha\SimpleAiClient\Contracts\Features;

use Liulinnuha\SimpleAiClient\DTOs\AiResponse;

interface GetBalance
{
    public function getBalance(): AiResponse;
}
