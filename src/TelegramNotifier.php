<?php

namespace Stezkoy\NouiTeleNotify;

class TelegramNotifier
{
    private const API_BASE_URL = 'https://api.telegram.org/bot';

    private const HTTP_TIMEOUT = 10;

    public function __construct(
        private readonly TelegramConfig $config,
    ) {}

    public function send(string $message): array
    {
        $url = self::API_BASE_URL . $this->config->botToken . '/sendMessage';

        $data = [
            'chat_id' => $this->config->chatId,
            'text' => $message,
            'parse_mode' => 'HTML',
            'disable_web_page_preview' => false,
        ];

        if ($this->config->topicId !== null) {
            $data['message_thread_id'] = $this->config->topicId;
        }

        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($jsonData === false) {
            return [
                'success' => false,
                'error' => 'Failed to encode message payload',
            ];
        }

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => $jsonData,
                'timeout' => self::HTTP_TIMEOUT,
                'ignore_errors' => true,
            ],
        ]);

        $response = @file_get_contents($url, false, $context);

        if ($response === false) {
            $error = error_get_last();

            return [
                'success' => false,
                'error' => $error['message'] ?? 'Failed to connect to Telegram API',
            ];
        }

        $result = json_decode($response, true);

        if (!is_array($result) || !($result['ok'] ?? false)) {
            $description = $result['description'] ?? 'Unknown Telegram API error';

            return [
                'success' => false,
                'error' => $description,
            ];
        }

        return ['success' => true];
    }
}