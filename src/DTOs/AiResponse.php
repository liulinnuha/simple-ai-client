<?php

namespace Liulinnuha\SimpleAiClient\DTOs;

class AiResponse
{
    public readonly bool $success;
    public readonly mixed $data;
    public readonly ?string $error;

    public function __construct(
        bool $success,
        mixed $data = null,
        ?string $error = null,
    ) {
        $this->success = $success;
        $this->data = $data;
        $this->error = $error;
    }

    /**
     * Buat instance dari array (misal hasil response)
     *
     * @param array $arr
     * @return self
     */
    public static function fromArray(array $arr): self
    {
        return new self(
            $arr['success'] ?? false,
            $arr['data'] ?? null,
            $arr['error'] ?? null,
        );
    }

    /**
     * Convert ke array jika perlu
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'data' => $this->data,
            'error' => $this->error,
        ];
    }

    /**
     * Helper untuk cek berhasil atau tidak
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Helper untuk cek error
     */
    public function hasError(): bool
    {
        return $this->error !== null;
    }
}
