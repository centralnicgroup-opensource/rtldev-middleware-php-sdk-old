# php-sdk


[![Packagist](https://img.shields.io/packagist/v/hexonet/php-sdk.svg)](https://packagist.org/packages/hexonet/php-sdk)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/hexonet/php-sdk.svg)](https://packagist.org/packages/hexonet/php-sdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PRs welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](https://github.com/hexonet/php-sdk/blob/master/CONTRIBUTING.md)
[![Slack Widget](https://camo.githubusercontent.com/984828c0b020357921853f59eaaa65aaee755542/68747470733a2f2f73332e65752d63656e7472616c2d312e616d617a6f6e6177732e636f6d2f6e6774756e612f6a6f696e2d75732d6f6e2d736c61636b2e706e67)](https://hexonet-sdk.slack.com/messages/CBF05V4CQ)

This module is a connector library for the insanely fast HEXONET Backend API. For further informations visit our [homepage](http://hexonet.net) and do not hesitate to [contact us](https://www.hexonet.net/contact).

## Resources

* [Usage Guide](https://github.com/hexonet/php-sdk/blob/master/README.md#how-to-use-this-module-in-your-project)
* [SDK Documenation](https://rawgit.com/hexonet/php-sdk/master/target/site/apidocs/net/hexonet/apiconnector/package-summary.html)
* [HEXONET Backend API Documentation](https://github.com/hexonet/hexonet-api-documentation/tree/master/API)
* [Release Notes](https://github.com/hexonet/php-sdk/releases)
* [Development Guide](https://github.com/hexonet/php-sdk/wiki/Development-Guide)

## How to use this module in your project

### Requirements

* Installed [composer](https://getcomposer.org/download/).

### Download from packagist

This module is available on the [PHP Package Registry](https://packagist.org/packages/hexonet/php-sdk).

Run `composer require "hexonet/php-sdk:1.0.0"`. You find the available version listed on the registry page.
In your script simply use `require 'vendor/autoload.php';` or `require 'vendor/hexonet/php-sdk';`

### Usage Examples

#### Session based API Communication

Not yet available, this needs a review of the SDK.

#### Sessionless API Communication

Have an eye on our [PHP SDK Demo App](https://github.com/hexonet/php-sdk-demo).

## Contributing

Please read [our development guide](https://github.com/hexonet/java-sdk/wiki/Development-Guide) for details on our code of conduct, and the process for submitting pull requests to us.

## Authors

* **Anthony Schneider** - *development* - [ISharky](https://github.com/isharky)
* **Kai Schwarz** - *development* - [PapaKai](https://github.com/papakai)

See also the list of [contributors](https://github/hexonet/php-sdk/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Documentation

Run `composer run-script docs`. It calls /usr/local/bin/phpDocumentor.phar which uses its config file phpdoc.dist.xml.
Documentation can be found under build/api.
