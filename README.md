# php-sdk


[![Packagist](https://img.shields.io/packagist/v/hexonet/php-sdk.svg)](https://packagist.org/packages/hexonet/php-sdk)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/hexonet/php-sdk.svg)](https://packagist.org/packages/hexonet/php-sdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PRs welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](https://github.com/hexonet/php-sdk/blob/master/CONTRIBUTING.md)
[![Slack Widget](https://camo.githubusercontent.com/984828c0b020357921853f59eaaa65aaee755542/68747470733a2f2f73332e65752d63656e7472616c2d312e616d617a6f6e6177732e636f6d2f6e6774756e612f6a6f696e2d75732d6f6e2d736c61636b2e706e67)](https://hexonet-sdk.slack.com/messages/CBF05V4CQ)

This module is a connector library for the insanely fast HEXONET Backend API. For further informations visit our [homepage](http://hexonet.net) and do not hesitate to contact us.

## Requirements

* Installed php plus php-xml, php-mbstring, php-xdebug, php-curl, php-intl, graphviz on OS-side.
* Installed [composer](https://getcomposer.org/download/).
* Installed [phpDocumentor.phar](https://github.com/phpDocumentor/phpDocumentor2/releases) under /usr/local/bin.

Make sure phpDocumentor.phar is executable.

For developers: Visual Studio Code with installed plugins for PHP Development described [here](https://code.visualstudio.com/docs/languages/php).

## Getting Started

Clone the git repository into your standard git folder by  `git clone https://github.com/hexonet/php-sdk`.
We have also a demo app available showing how to integrate and use our SDK. See [here](https://github.com/hexonet/php-sdk-demo).

### For development purposes

Now you can already start working on the project.

### How to use this module in your project

Run `composer require "hexonet/php-sdk:1.0.0"`. You may check packagist/github for a newer release version.
In your script simply use `require 'vendor/autoload.php';` or `require 'vendor/hexonet/php-sdk';`

## Development

### Run Unit Tests and Code Styling Check

Run `composer run-script test`.
First this executes all automated tests which can be found in subfolder "tests".
Then it uses PHPCBF to autfix source code styling issues.
Then it uses PHPCS to check if there are still styling issues left and displays them.

### Release an Update

Simply make a PR / merge request. We care about versioning.

## Contributing

Please read [CONTRIBUTING.md](https://github.com/hexonet/php-sdk/blob/master/CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/hexonet/php-sdk/tags).

## Authors

* **Anthony Schneider** - *development* - [ISharky](https://github.com/isharky)
* **Kai Schwarz** - *development* - [PapaKai](https://github.com/papakai)

See also the list of [contributors](https://github/hexonet/php-sdk/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details

## How-to-use Examples

### Session based API Communication

Not yet available, this needs a review of the SDK.

### Sessionless API Communication

Have an eye on our [PHP SDK Demo App](https://github.com/hexonet/php-sdk-demo).

## Documentation

Run `composer run-script docs`. It calls /usr/local/bin/phpDocumentor.phar which uses its config file phpdoc.dist.xml.
Documentation can be found under build/api.
