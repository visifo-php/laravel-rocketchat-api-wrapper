# Rocket.chat API wrapper for Laravel.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/visifo-php/laravel-rocketchat-api-wrapper.svg?style=flat-square)](https://packagist.org/packages/visifo-php/laravel-rocketchat-api-wrapper)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/visifo-php/laravel-rocketchat-api-wrapper/run-tests?label=tests)](https://github.com/visifo-php/laravel-rocketchat-api-wrapper/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/visifo-php/laravel-rocketchat-api-wrapper/Check%20&%20fix%20styling?label=code%20style)](https://github.com/visifo-php/laravel-rocketchat-api-wrapper/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/visifo-php/laravel-rocketchat-api-wrapper.svg?style=flat-square)](https://packagist.org/packages/visifo-php/laravel-rocketchat-api-wrapper)

---

This is a Laravel RocketChat REST API Wrapper. It's simple to use, typesafe and object-oriented.

## Installation

You can install the package via composer:

```bash
composer require visifo-php/laravel-rocketchat-api-wrapper
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="visifo\Rocket\RocketServiceProvider" --tag="laravel-rocketchat-api-wrapper-config"
```

You have to specify your RocketChat Url and either the UserId and AuthToken or UserName and Password to authenticate
with the RocketChat API in your Laravel .env

```dotenv
ROCKET_URL=your-rocketchat.de

ROCKET_USER_ID=123456
ROCKET_AUTH_TOKEN=987654
#or
ROCKET_USER_NAME=myrocketuser
Rocket_USER_PASSWORD=mypassword
```

## Usage

You can send Requests to an Endpoint via the Endpoint Objects. You can get them from the RocketChat Client like this

```php
$channelsEndpoint = \visifo\Rocket\rocketChat()->channels
$chatEndpoint = \visifo\Rocket\rocketChat()->chat
$commandsEndpoint = \visifo\Rocket\rocketChat()->commands
$rolesEndpoint = \visifo\Rocket\rocketChat()->roles
$usersEndpoint = \visifo\Rocket\rocketChat()->users
```

From there you can make API calls

```php
$channel = $channelsEndpoint->create("myChannel");
// $channel has Type: visifo\Rocket\Objects\Channels\Channel

$channelsEndpoint->setTopic($channel->id, "myTopic")
```

All functions who return the Response from RocketChat will deserialize it into its own simplified Object. Also, some properties gets renamed to something more simple or meaningful,
for example "t" to "type" or "_id" to "id"

Alternatively you can make Requests directly via the RocketChat Client which will result in the raw stdClass you get
from the RocketChat API

```php
$rocketChatClient = \visifo\Rocket\rocketChat()

$channel = $rocketChatClient->post("channels.create", ['name' => 'myChannel']);
// $channel has Type: stdClass

$rocketChatClient->post("channels.setTopic", ['roomId' => $channel->_id, 'topic' => 'myTopic'])
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
