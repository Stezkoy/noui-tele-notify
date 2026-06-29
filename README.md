# NOUI Telegram Notify

A Flarum v2 extension that sends notifications about new discussions and new posts to a Telegram channel or group. All configuration is done via `config.php` — no admin panel needed.

## Features

- Notification on new discussion — title, author, date, excerpt (200 chars), link
- Notification on new post in an existing discussion — thread title, author, date, excerpt (200 chars), link
- Support for Telegram Group topics (optional `topic_id`)
- Bilingual interface: Russian and English
- HTML formatting with emoji

## Installation

Install the extension via Composer:

```bash
composer require stezkoy/noui-tele-notify
```

Then run Flarum migrations:

```bash
php flarum migrate
php flarum cache:clear
```

## Configuration

The extension has no admin panel settings. Configure it in your forum's `config.php` under the `stezkoy-noui-tele-notify` key:

### Minimal configuration

```php
<?php return array(
    // ... other Flarum settings

    'stezkoy-noui-tele-notify' => array(
        'bot_token' => '1234567890:AAF1Cc2Dd3Ee4Ff5Gg6Hh7Ii8Jj9Kk0Ll',
        'chat_id' => '-1001234567890',
    ),
);
```

### Full configuration (with Telegram Group topic)

```php
<?php return array(
    // ... other Flarum settings

    'stezkoy-noui-tele-notify' => array(
        'bot_token' => '1234567890:AAF1Cc2Dd3Ee4Ff5Gg6Hh7Ii8Jj9Kk0Ll',
        'chat_id' => '-1001234567890',
        'topic_id' => 123,  // Group topic ID (optional)
    ),
);
```

### Parameters

| Parameter   | Type   | Required | Description                          |
| ----------- | ------ | -------- | ------------------------------------ |
| `bot_token` | string | yes      | Telegram bot token from BotFather    |
| `chat_id`   | string | yes      | Chat or group ID to send messages to |
| `topic_id`  | int    | no       | Telegram Group topic ID              |

## How to get chat_id

1. Add the bot to your group
2. Write any message in the group
3. Request: `https://api.telegram.org/bot<TOKEN>/getUpdates`
4. Find `chat.id` in the response (for groups it is negative, e.g. `-1001234567890`)

## Requirements

- PHP ^8.1
- Flarum ^2.0

## Author

**stezkoy**

## License

GPL v3