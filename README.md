# Rocket.chat API wrapper for Laravel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/visifo-php/laravel-rocketchat-api-wrapper.svg?style=flat-square)](https://packagist.org/packages/visifo-php/laravel-rocketchat-api-wrapper)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/visifo-php/laravel-rocketchat-api-wrapper/run-tests?label=tests)](https://github.com/visifo-php/laravel-rocketchat-api-wrapper/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/visifo-php/laravel-rocketchat-api-wrapper/Check%20&%20fix%20styling?label=code%20style)](https://github.com/visifo-php/laravel-rocketchat-api-wrapper/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/visifo-php/laravel-rocketchat-api-wrapper.svg?style=flat-square)](https://packagist.org/packages/visifo-php/laravel-rocketchat-api-wrapper)

---

## Installation

You can install the package via composer:

```bash
composer require visifo-php/laravel-rocketchat-api-wrapper
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="visifo\Rocket\RocketServiceProvider" --tag="laravel-rocketchat-api-wrapper-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$laravel-rocketchat-api-wrapper = new visifo\Rocket();
echo $laravel-rocketchat-api-wrapper->echoPhrase('Hello, visifo!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Sergej Tihonov](https://github.com/Sergej-Tihonov)
- [Luka Heddens](https://github.com/frschi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
