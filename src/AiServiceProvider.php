<?php

namespace Liulinnuha\SimpleAiClient;

use Illuminate\Support\ServiceProvider;

class AiServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register(): void
    {
        // Merge default config
        $this->mergeConfigFrom(__DIR__ . '/../config/ai.php', 'ai');

        // Bind Manager as singleton
        $this->app->singleton(Manager::class, function ($app) {
            return new Manager($app);
        });

        // Bind Client with alias for Facade usage
        $this->app->singleton('ai', function ($app) {
            return new Client($app->make(Manager::class));
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes(
            [
                __DIR__ . '/../config/ai.php' => config_path('ai.php'),
            ],
            'config',
        );

        // Register commands (only in console)
        if ($this->app->runningInConsole()) {
            $this->commands([Commands\AiTestCommand::class]);
        }
    }
}
