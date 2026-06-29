<?php

use Flarum\Discussion\Event\Started;
use Flarum\Extend;
use Flarum\Post\Event\Posted;
use Stezkoy\NouiTeleNotify\NewDiscussionListener;
use Stezkoy\NouiTeleNotify\NewPostListener;
use Stezkoy\NouiTeleNotify\TelegramServiceProvider;

return [
    new Extend\ServiceProvider(TelegramServiceProvider::class),

    (new Extend\Event())
        ->listen(Started::class, [NewDiscussionListener::class, 'handle'])
        ->listen(Posted::class, [NewPostListener::class, 'handle']),

    (new Extend\LanguagePack())
        ->locale('en', __DIR__ . '/locale/en.yml')
        ->locale('ru', __DIR__ . '/locale/ru.yml'),
];