# Generate and save all public streaming links relating to a specific song or album.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/gnarhard/songlink.svg?style=flat-square)](https://packagist.org/packages/gnarhard/songlink)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/gnarhard/songlink/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/gnarhard/songlink/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/gnarhard/songlink/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/gnarhard/songlink/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/gnarhard/songlink.svg?style=flat-square)](https://packagist.org/packages/gnarhard/songlink)

Allow dynamic songlink generation and display.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/songlink.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/songlink)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require gnarhard/songlink
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="songlink-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="songlink-config"
```

This is the contents of the published config file:

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Odesli API URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL for the Odesli (SongLink) API.
    |
    */
    'api_url' => env('SONGLINK_API_URL', 'https://api.song.link/v1-alpha.1/links'),
];
```

## Usage

```bash
art songlink:store "<spotify_url>" "<title>" <slug> <is_single>
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Grayson Erhard](https://github.com/gnarhard)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
