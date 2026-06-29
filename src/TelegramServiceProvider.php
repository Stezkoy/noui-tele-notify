<?php

namespace Stezkoy\NouiTeleNotify;

use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Foundation\Config;

class TelegramServiceProvider extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(TelegramConfig::class, function ($container) {
            $config = $container->make(Config::class);
            $settings = $config->offsetGet('stezkoy-noui-tele-notify') ?? [];

            return new TelegramConfig(
                botToken: $settings['bot_token'] ?? '',
                chatId: $settings['chat_id'] ?? '',
                topicId: $settings['topic_id'] ?? null,
            );
        });

        $this->container->singleton(TelegramNotifier::class, function ($container) {
            $config = $container->make(TelegramConfig::class);

            return new TelegramNotifier($config);
        });
    }
}