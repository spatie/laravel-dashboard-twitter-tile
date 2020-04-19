<?php

namespace Spatie\TwitterTile\Commands;

use Illuminate\Console\Command;
use Spatie\LaravelTwitterStreamingApi\TwitterStreamingApi;
use Spatie\TwitterStreamingApi\PublicStream;
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

        app(PublicStream::class)
            ->whenHears($configuration['listenFor'], function (array $tweetProperties) {
                TwitterStore::make()->addTweet();
            });

        app(TwitterStreamingApi::class)
            ->publicStream()
            ->whenHears([
                'spatie.be',
                '@spatie_be',
                'github.com/spatie',
            ], function (array $tweetProperties) {
                TwitterStore::make()->addTweet($tweetProperties);
            })
            ->startListening();
    }
}
