<?php

namespace Spatie\TwitterTile\Commands;

use Illuminate\Console\Command;
use Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory;
use Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Tweet;
use Spatie\TwitterLabs\FilteredStream\Rule;
use Spatie\TwitterTile\TwitterStore;

class ListenForMentionsCommand extends Command
{
    protected $signature = 'dashboard:listen-twitter-mentions {configurationName=default}';

    protected $description = 'Listen for mentions on Twitter';

    public function handle()
    {
        $configurationName = $this->argument('configurationName');

        $configuration = config("dashboard.tiles.twitter.configurations.{$configurationName}");

        if (is_null($configuration)) {
            $this->error("There is no configuration named `{$configurationName}`");

            return -1;
        }

        $this->info("Listening for mentions for configuration named `{$configurationName}`...");

        $filteredStream = FilteredStreamFactory::create(
            $configuration['consumer_key'],
            $configuration['consumer_secret'],
        );

        $rules = array_map(fn ($rule) => new Rule($rule), $configuration['listen_for']);
        $filteredStream->setRules(...$rules);

        $filteredStream
            ->onTweet(fn (Tweet $tweet) => TwitterStore::make($configurationName)->addTweet($tweet))
            ->start();
    }
}
