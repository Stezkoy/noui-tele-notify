<?php

namespace Stezkoy\NouiTeleNotify;

use Flarum\Discussion\Event\Started;
use Flarum\Http\UrlGenerator;
use Flarum\Locale\Translator;

class NewDiscussionListener
{
    public function __construct(
        private readonly TelegramNotifier $notifier,
        private readonly UrlGenerator $url,
        private readonly Translator $translator,
    ) {}

    public function handle(Started $event): void
    {
        $discussion = $event->discussion;
        $post = $event->post;

        $title = $discussion->title;

        $content = '';
        if ($post !== null && !empty($post->content)) {
            $content = strip_tags((string) $post->content);
        }
        $excerpt = mb_substr($content, 0, 200);
        if (mb_strlen($content) > 200) {
            $excerpt .= '…';
        }

        $tagsBlock = '';
        $tags = $discussion->tags;
        if ($tags !== null && !$tags->isEmpty()) {
            $tagNames = $tags->pluck('name')->implode(', ');
            $tagsBlock = '🏷️ ' . $this->escape($tagNames);
        }

        $discussionUrl = $this->url->to('forum')->route('discussion', ['id' => $discussion->id]);

        $message = $this->translator->trans('stezkoy-noui-tele-notify.forum.new_discussion', [
            '{title}' => $this->escape($title),
            '{excerpt}' => $this->escape($excerpt),
            '{tags}' => $tagsBlock,
            '{url}' => $discussionUrl,
        ]);

        $this->notifier->send($message);
    }

    private function escape(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}