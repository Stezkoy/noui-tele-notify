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

        $user = $post?->user;
        $authorName = $user?->display_name ?? 'Unknown';

        $createdAt = $post?->created_at;
        $date = $createdAt ? $createdAt->format('d.m.Y H:i') : '—';

        $content = '';
        if ($post !== null && !empty($post->content)) {
            $content = strip_tags((string) $post->content);
        }
        $excerpt = mb_substr($content, 0, 200);
        if (mb_strlen($content) > 200) {
            $excerpt .= '…';
        }

        $tagsString = '';
        if (method_exists($discussion, 'tags')) {
            try {
                $tags = $discussion->tags;
                if ($tags !== null && $tags->isNotEmpty()) {
                    $tagNames = [];
                    foreach ($tags as $tag) {
                        $tagNames[] = '#' . $tag->name;
                    }
                    $tagsString = implode(' ', $tagNames);
                }
            } catch (\Throwable $e) {
                // tags extension not available
            }
        }

        $discussionUrl = $this->url->to('forum')->route('discussion', ['id' => $discussion->id]);

        $message = $this->translator->trans('stezkoy-noui-tele-notify.forum.new_discussion', [
            '{title}' => $this->escape($title),
            '{tags}' => $this->escape($tagsString),
            '{author}' => $this->escape($authorName),
            '{date}' => $date,
            '{excerpt}' => '<i>' . $this->escape($excerpt) . '</i>',
            '{url}' => $discussionUrl,
        ]);

        $this->notifier->send($message);
    }

    private function escape(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}