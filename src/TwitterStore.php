<?php

namespace Spatie\TwitterTile;

use Spatie\Dashboard\Models\Tile;
use Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Tweet as TwitterLabsTweet;

class TwitterStore
{
    private Tile $tile;

    public static function make(string $configurationName)
    {
        return new static($configurationName);
    }

    public function __construct(string $configurationName)
    {
        $this->tile = Tile::firstOrCreateForName("twitter_{$configurationName}");
    }

    public function addTweet(TwitterLabsTweet $tweet)
    {
        $tweets = $this->tile->getData('tweets') ?? [];

        array_unshift($tweets, $tweet->toArray());

        $tweets = array_slice($tweets, 0, 50);

        $this->tile->putData('tweets', $tweets);
    }

    public function tweets(): array
    {
        return collect($this->tile->getData('tweets') ?? [])
            ->map(fn (array $tweetAttributes) => new Tweet($tweetAttributes))
            ->reject(fn (Tweet $tweet) => $tweet->bySpatie())
            ->toArray();
    }
}
