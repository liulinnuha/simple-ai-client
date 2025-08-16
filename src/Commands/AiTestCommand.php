<?php

namespace Liulinnuha\SimpleAiClient\Commands;

use Exception;
use Illuminate\Console\Command;
use Liulinnuha\SimpleAiClient\Client;

class AiTestCommand extends Command
{
    protected $signature = 'ai:test
        {prompt? : Text prompt or input for AI}
        {--provider= : Select AI provider to use}
        {--method=chat : AI method to call (chat|embed|moderate)}
    ';

    protected $description = 'Test AI SDK: chat, embed, moderate with custom provider and input';

    public function handle(Client $client)
    {
        $prompt = $this->argument('prompt');
        $providerName = $this->option('provider');
        $method = strtolower($this->option('method') ?? 'chat');

        if (!$prompt) {
            $prompt = $this->ask('Enter text prompt/input for AI');
        }

        $this->info('Provider: ' . ($providerName ?? 'default'));
        $this->info("Method: $method");
        $this->info("Input: $prompt");

        try {
            $clientInstance = $providerName
                ? $client->provider($providerName)
                : $client;

            switch ($method) {
                case 'chat':
                    $response = $clientInstance->chat([
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt],
                        ],
                    ]);
                    break;

                case 'embed':
                    $response = $clientInstance->embed([
                        'input' => $prompt,
                    ]);
                    break;

                case 'moderate':
                    // Moderate method typically takes string input and empty options array for example
                    $response = $clientInstance->moderate($prompt);
                    break;

                default:
                    $this->error(
                        "Method '$method' not recognized. Use chat, embed, or moderate.",
                    );
                    return 1;
            }

            $this->line(
                json_encode(
                    $response,
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES,
                ),
            );
        } catch (Exception $e) {
            $this->error('AI SDK error: ' . $e->getMessage());
            return 1;
        } catch (\Throwable $e) {
            $this->error('Error: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
