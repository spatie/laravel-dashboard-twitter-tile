# A tile to display Twitter mentions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-dashboard-twitter-tile.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-dashboard-twitter-tile)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-dashboard-twitter-tile/run-tests?label=tests)](https://github.com/spatie/laravel-dashboard-twitter-tile/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-dashboard-twitter-tile.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-dashboard-twitter-tile)

This tile can used on the [Laravel Dashboard](https://github.com/spatie/laravel-dashboard) to display Twitter mentions.

![screenshot](TODO: add link)

## Support us

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us). 

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/laravel-dashboard-twitter-tile
```

You must also [set up](https://github.com/spatie/laravel-google-calendar#installation) the `spatie/laravel-google-calendar` package. That package will fetch data for Google Calendar. Here are instructions that show how you can [obtain credentials to communicate with Google Calendar](https://github.com/spatie/laravel-google-calendar#how-to-obtain-the-credentials-to-communicate-with-google-calendar).

In the `dashboard` config file, you must add this configuration in the `tiles` key. You can add a configuration in the `configurations` key per Twitter tile that you want to display. Any tweet that contains one of the strings in `listen_for` will be display on the dashboard.

```php
// in config/dashboard.php

return [
    // ...
    'tiles' => [
          'twitter' => [
                'configurations' => [
                    'default' => [
                        'access_token' => env('TWITTER_ACCESS_TOKEN'),
                        'access_token_secret' => env('TWITTER_ACCESS_TOKEN_SECRET'),
                        'consumer_key' => env('TWITTER_CONSUMER_KEY'),
                        'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
                        'listen_for' => [
                            // 
                        ],
                    ],
                ],
            ],
    ],
];
```

Under the hood this package uses [`spatie/laravel-twitter-streaming-api`](https://github.com/spatie/laravel-twitter-streaming-api). Take a look in the readme of the package to learn [how you can get the values](https://github.com/spatie/laravel-twitter-streaming-api#getting-credentials) for `access_token`,  `access_token_secret`, `consumer_key`, and `consumer_key`.

## Usage

To starting listening for incoming tweets of the configuration named `default` must execute this command:

```bash
php artisan dashboard:listen-twitter-mentions
``

This command will never end. In production should probably want to use something like Supervisord to keep this this task running and to automatically start it when your sytem restarts.

To start listening for tweets of another configuration, simply add the name of the configuration as an arugment.

```bash
php artisan dashboard:listen-twitter-mentions alternate-configuration-name
```

In your dashboard view you use the `livewire:twitter-tile` component to display tweets of the default configuration.

```html
<x-dashboard>
    <livewire:twitter-tile position="a1:a6"/>
</x-dashboard>
```

To display tweets of another configuration, pass the name of the configuration to the `configuration-name` prop.

```html
<x-dashboard>
    <livewire:twitter-tile position="a1:a6" configuration-name="alternate-configuration-name"/>
</x-dashboard>
```

### Customizing the view

If you want to customize the view used to render this tile, run this command:

```bash
php artisan vendor:publish --provider="Spatie\TwitterTile\TwitterTileServiceProvider" --tag="dashboard-twitter-tile-views"
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
