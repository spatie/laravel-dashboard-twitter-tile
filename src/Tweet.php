<?php

namespace Spatie\TwitterTile;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Tweet
{
    private array $tweetProperties;

    private ?Tweet $quotedTweet;

    public function __construct(array $tweetProperties)
    {
        $this->tweetProperties = $tweetProperties;

        if ($this->hasQuote()) {
            $quote = collect($this->tweetProperties['referenced_tweets'] ?? [])
                ->where('type', 'quoted')
                ->first();

            $this->quotedTweet = new Tweet($this->getIncluded($quote['id'], 'tweet'));
        }
    }

    public function authorScreenName(): string
    {
        return "@{$this->getAuthor()['username']}";
    }

    public function authorName(): string
    {
        return $this->getAuthor()['name'];
    }

    public function authorAvatar(): string
    {
        return $this->getAuthor()['profile_image_url'];
    }

    public function image(): string
    {
        $media = Arr::get($this->tweetProperties, 'attachments.media_keys.0', null);

        if (! $media) {
            return '';
        }

        return $this->getMedia($media['id'])['url']
            ?? $this->getMedia($media['id'])['preview_image_url']
            ?? '';
    }

    public function date(): ?Carbon
    {
        $timestamp = strtotime($this->tweetProperties['created_at']);

        if (! $timestamp) {
            return null;
        }

        return Carbon::createFromTimestamp($timestamp);
    }

    public function isRetweet(): bool
    {
        return collect($this->tweetProperties['referenced_tweets'] ?? [])
            ->where('type', 'retweeted')
            ->isNotEmpty();
    }

    public function bySpatie(): bool
    {
        return $this->authorScreenName() === '@spatie.be';
    }

    public function hasQuote(): bool
    {
        return collect(Arr::get($this->tweetProperties, 'referenced_tweets', []))
            ->where('type', 'quoted')
            ->isNotEmpty();
    }

    public function quote(): ?Tweet
    {
        return $this->quotedTweet;
    }

    public function text()
    {
        return $this->tweetProperties['text'];
    }

    public function html(): string
    {
        $html = $this->text();

        preg_replace("/(#\w*[0-9a-zA-Z]+\w*[0-9a-zA-Z])/", '<span class="tweet__body__hashtag">$1</span>', $html);
        preg_replace("/(@\w*[0-9a-zA-Z]+\w*[0-9a-zA-Z])/", '<span class="tweet__body__handle">$1</span>', $html);

        return $html;
    }

    protected function getAuthor(): ?array
    {
        return $this->getIncluded($this->tweetProperties['author_id'], 'user');
    }

    protected function getMedia(string $mediaKey): ?array
    {
        return collect(Arr::get($this->tweetProperties, 'includes.media', []))
            ->where('media_key', $mediaKey)
            ->first();
    }

    protected function getIncluded(string $id, string $type = 'tweet'): ?array
    {
        $type = Str::plural($type);

        return collect(Arr::get($this->tweetProperties, "includes.{$type}", []))
            ->where('id', $id)
            ->first();
    }
}
