<?php

namespace Liulinnuha\SimpleAiClient\Exceptions;

use RuntimeException;

class FeatureNotSupportedException extends RuntimeException
{
    public static function for(string $feature, string $provider): self
    {
        return new self(
            "Feature '{$feature}' is not supported by provider '{$provider}'.",
        );
    }
}
