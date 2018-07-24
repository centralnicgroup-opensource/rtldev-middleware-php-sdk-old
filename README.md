# php-sdk


[![Packagist](https://img.shields.io/packagist/v/hexonet/php-sdk.svg)](https://packagist.org/packages/hexonet/php-sdk)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/hexonet/php-sdk.svg)](https://packagist.org/packages/hexonet/php-sdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PRs welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](https://github.com/hexonet/php-sdk/blob/master/CONTRIBUTING.md)
[![Slack Widget](https://camo.githubusercontent.com/984828c0b020357921853f59eaaa65aaee755542/68747470733a2f2f73332e65752d63656e7472616c2d312e616d617a6f6e6177732e636f6d2f6e6774756e612f6a6f696e2d75732d6f6e2d736c61636b2e706e67)](https://hexonet-sdk.slack.com/messages/CBF05V4CQ)

This module is a connector library for the insanely fast HEXONET Backend API. For further informations visit our [homepage](http://hexonet.net) and do not hesitate to [contact us](https://www.hexonet.net/contact).

## Resources

* [Usage Guide](https://github.com/hexonet/php-sdk/blob/master/README.md#how-to-use-this-module-in-your-project)
* [SDK Documenation](https://rawgit.com/hexonet/php-sdk/master/build/api/index.html)
* [HEXONET Backend API Documentation](https://github.com/hexonet/hexonet-api-documentation/tree/master/API)
* [Release Notes](https://github.com/hexonet/php-sdk/releases)
* [Development Guide](https://github.com/hexonet/php-sdk/wiki/Development-Guide)

## How to use this module in your project

We have also a demo app available showing how to integrate and use our SDK. See [here](https://github.com/hexonet/php-sdk-demo).

### Requirements

* Installed php and php-curl
* Installed [composer](https://getcomposer.org/download/).

### Download from packagist

This module is available on the [PHP Package Registry](https://packagist.org/packages/hexonet/php-sdk).

Run `composer require "hexonet/php-sdk:*"` to get the latest version downloaded and added to composer.json.
In your script simply use `require 'vendor/autoload.php';` or `require 'vendor/hexonet/php-sdk';`

NOTE: The above will also set `"hexont/php-sdk": "*"` as dependency entry in your composer.json. When running `composer install` this would always install the latest release version. This is dangerous for production systems as major version upgrades may come with breaking changes and are then incompatible with your app. For production systems we suggest to use a version dependent syntax, e.g. `composer require "hexonet/php-sdk:v3.0.3"`.
You can find the versions listed at packagist or at github in the release / tag overview.

### Usage Examples

Please have an eye on our [HEXONET Backend API documentation](https://github.com/hexonet/hexonet-api-documentation/tree/master/API). Here you can find information on available Commands and their response data.

#### Session based API Communication

Not yet available, this needs a review of the SDK.

#### Sessionless API Communication

```php
require __DIR__ . '/vendor/autoload.php';

// --- SESSIONLESS API COMMUNICATION ---
$api = \HEXONET\Connection::connect(array(
    "url" => "https://coreapi.1api.net/api/call.cgi",
    "login" => "test.user",
    "password" => "test.passw0rd",
    "entity" => "1234",
    //"remoteaddr" => "1.2.3.4" //optional: use this in case of ip filter setting
));

$r = $api->call(array(
    "COMMAND" => "StatusAccount"
));
echo "<pre>" . htmlspecialchars(print_r($r->asHash(), true)) . "</pre>";
```

## Contributing

Please read [our development guide](https://github.com/hexonet/php-sdk/wiki/Development-Guide) for details on our code of conduct, and the process for submitting pull requests to us.

## Authors

* **Anthony Schneider** - *development* - [ISharky](https://github.com/isharky)
* **Kai Schwarz** - *development* - [PapaKai](https://github.com/papakai)

See also the list of [contributors](https://github.com/hexonet/php-sdk/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
