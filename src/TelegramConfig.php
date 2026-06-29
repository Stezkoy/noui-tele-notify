<?php

namespace Stezkoy\NouiTeleNotify;

class TelegramConfig
{
    public function __construct(
        public readonly string $botToken,
        public readonly string $chatId,
        public readonly ?int $topicId = null,
    ) {}
}