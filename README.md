# php-sdk

[![semantic-release](https://img.shields.io/badge/%20%20%F0%9F%93%A6%F0%9F%9A%80-semantic--release-e10079.svg)](https://github.com/semantic-release/semantic-release)
[![Build Status](https://travis-ci.com/hexonet/php-sdk.svg?branch=master)](https://travis-ci.org/hexonet/php-sdk)
[![Packagist](https://img.shields.io/packagist/v/hexonet/php-sdk.svg)](https://packagist.org/packages/hexonet/php-sdk)
[![PHP from Packagist](https://img.shields.io/packagist/php-v/hexonet/php-sdk.svg)](https://packagist.org/packages/hexonet/php-sdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PRs welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](https://github.com/hexonet/php-sdk/blob/master/CONTRIBUTING.md)

This module is a connector library for the insanely fast HEXONET Backend API. For further informations visit our [homepage](http://hexonet.net) and do not hesitate to [contact us](https://www.hexonet.net/contact).

## Resources

* [Usage Guide](https://github.com/hexonet/php-sdk/blob/master/README.md#how-to-use-this-module-in-your-project)
* [Migration Guide](https://github.com/hexonet/php-sdk/wiki/Migration-Guide)
* [SDK Documenation](https://rawgit.com/hexonet/php-sdk/master/build/api/index.html)
* [HEXONET Backend API Documentation](https://github.com/hexonet/hexonet-api-documentation/tree/master/API)
* [Release Notes](https://github.com/hexonet/php-sdk/releases)
* [Development Guide](https://github.com/hexonet/php-sdk/wiki/Development-Guide)

## Features

* Automatic IDN conversion to punycode (our API accepts only punycode format in commands)
* Allows nested associative arrays in API commands to improve for bulk parameters
* Connecting and communication with our API
* Possibility to use a custom mechanism for debug mode
* Several ways to access and deal with response data
* Getting the command again returned together with the response
* Sessionless communication
* Session based communication
* Possibility to save API session identifier in PHP session
* Configure a Proxy for API communication
* Configure a Referer for API communication
* High Performance Proxy Setup

## How to use this module in your project

We have also a demo app available showing how to integrate and use our SDK. See [here](https://github.com/hexonet/php-sdk-demo).

### Requirements

* Installed php (>= v5.6.0) and php-curl
* Installed [composer](https://getcomposer.org/download/).

### Download from packagist

This module is available on the [PHP Package Registry](https://packagist.org/packages/hexonet/php-sdk).

Run `composer require "hexonet/php-sdk:*"` to get the latest version downloaded and added to composer.json.
In your script simply use `require 'vendor/autoload.php';` or `require 'vendor/hexonet/php-sdk';`

NOTE: The above will also set `"hexonet/php-sdk": "*"` as dependency entry in your composer.json. When running `composer install` this would always install the latest release version. This is dangerous for production systems as major version upgrades may come with breaking changes and are then incompatible with your app. For production systems we suggest to use a version dependent syntax, e.g. `composer require "hexonet/php-sdk:v3.0.3"`.
You can find the versions listed at packagist or at github in the release / tag overview.

#### Alternatives

Of course you could also using composer to install it from github or using the PHAR archives we offer for download in release overview, but the previous approach is the one we suggest.

### High Performance Proxy Setup

Long distances to our main data center in Germany may result in high network latencies. If you encounter such problems, we highly recommend to use this setup, as it uses persistent connections to our API server and the overhead for connection establishments is omitted.

#### Step 1: Required Apache2 packages / modules

*At least Apache version 2.2.9* is required.

The following Apache2 modules must be installed and activated:

```bash
proxy.conf
proxy.load
proxy_http.load
ssl.conf # for HTTPs connection to our API server
ssl.load # for HTTPs connection to our API server
```

#### Step 2: Apache configuration

An example Apache configuration with binding to localhost:

```bash
<VirtualHost 127.0.0.1:80>
    ServerAdmin webmaster@localhost

    ServerSignature Off

    SSLProxyEngine on
    ProxyPass /api/call.cgi https://api.ispapi.net/api/call.cgi min=1 max=2
    <Proxy *>
        Order Deny,Allow
        Deny from none
        Allow from all
    </Proxy>
</VirtualHost>
```

After saving your configuration changes please restart the Apache webserver.

#### Step 3: Using this setup

```php
$cl = new \HEXONET\APIClient();
$cl->useOTESystem()//LIVE System would be used otherwise by default
   ->useHighPerformanceConnectionSetup()//Default Connection Setup would be used otherwise by default
   ->setCredentials("test.user", "test.passw0rd");
$r = $cl->request(["COMMAND" => "StatusAccount"]);
```

So, what happens in code behind the scenes? We communicate with localhost (so our proxy setup) that passes the requests to the HEXONET API.
Of course we can't activate this setup by default as it is based on Steps 1 and 2. Otherwise connecting to our API wouldn't work.

Just in case the above port or ip address can't be used, use function setURL instead to set a different URL / Port.
`http://127.0.0.1/api/call.cgi` is the default URL for the High Performance Proxy Setup.
e.g. `$cl->setURL("http://127.0.0.1:8765/api/call.cgi");` would change the port. Configure that port also in the Apache Configuration (-> Step 2)!

Don't use `https` for that setup as it leads to slowing things down as of the https `overhead` of securing the connection. In this setup we just connect to localhost, so no direct outgoing network traffic using `http`. The apache configuration finally takes care passing it to `https` for the final communication to the HEXONET API.

### Customize Logging / Outputs

When having the debug mode activated \HEXONET\Logger will be used for doing outputs.
Of course it could be of interest for integrators to look for a way of getting this replaced by a custom mechanism like forwarding things to a 3rd-party software, logging into file or whatever.

```php
$cl = new \HEXONET\APIClient();
$cl->useOTESystem()//LIVE System would be used otherwise by default
   ->enableDebugMode()//Default Connection Setup would be used otherwise by default
   ->setCustomLogger(new MyCustomerLogger())
   ->setCredentials("test.user", "test.passw0rd");
$r = $cl->request(["COMMAND" => "StatusAccount"]);
```

NOTE: Find an example for a custom logger class implementation in `src/CustomLogger.php`. If you have questions, feel free to open a github issue.

### Usage Examples

Please have an eye on our [HEXONET Backend API documentation](https://github.com/hexonet/hexonet-api-documentation/tree/master/API). Here you can find information on available Commands and their response data.

#### Session based API Communication

Available since version 4.x!

```php
$cl = new \HEXONET\APIClient();
$cl->useOTESystem()//LIVE System would be used otherwise by default
   ->setCredentials("test.user", "test.passw0rd");
$r = $cl->login();
// or this line for using 2FA
// $r = $cl->login('.. here your otp code ...');
if ($r->isSuccess()){
    echo "LOGIN SUCCEEDED.<br/>";

    // Now reuse the created API session for further request
    // You don't have to care about anything!
    $r = $cl->request(array(
        "COMMAND" => "StatusAccount"
    ));
    echo "<pre>" . htmlspecialchars(print_r($r->getHash(), true)) . "</pre>";

    // Perform session close and logout
    $r = $cl->logout();
    if ($r->isSuccess()){
        echo "LOGOUT SUCCEEDED.<br/>";
    } else {
        echo "LOGOUT FAILED.<br/>";
    }
}
else {
    echo "LOGIN FAILED.<br/>";
}
```

##### Save session config into PHP Session

If you're realizing your own frontend on top, you need a solution to keep the Backend API Session that the PHP-SDK wraps internally to be reusable in further page loads. This can be achieved by

```php
// right after successful login
$cl->saveSession($_SESSION);
```

and

```php
// for every further request
$cl->reuseSession($_SESSION);
```

#### Sessionless API Communication

```php
require __DIR__ . '/vendor/autoload.php';

// --- SESSIONLESS API COMMUNICATION ---
$cl = new \HEXONET\APIClient();
$cl->useOTESystem()//LIVE System would be used otherwise by default
   // ->setRemoteIPAddress("1.2.3.4:80"); // provide ip address used for active ip filter
   ->setCredentials("test.user", "test.passw0rd");
$r = $cl->request(array(
    "COMMAND" => "StatusAccount"
));
echo "<pre>" . htmlspecialchars(print_r($r->getHash(), true)) . "</pre>";
```

#### Using array-based notation for bulk parameters in command [SINCE 5.2.0]

Use the below to improve code a bit:

```php
require __DIR__ . '/vendor/autoload.php';

$cl = new \HEXONET\APIClient();
$cl->useOTESystem()
   ->setCredentials("test.user", "test.passw0rd");
$r = $cl->request([
    "COMMAND" => "QueryDomainOptions"
    "DOMAIN" => ["example1.com", "example2.com"]
]);
echo "<pre>" . htmlspecialchars(print_r($r->getHash(), true)) . "</pre>";
```

instead of:

```php
require __DIR__ . '/vendor/autoload.php';

$cl = new \HEXONET\APIClient();
$cl->useOTESystem()
   ->setCredentials("test.user", "test.passw0rd");
$r = $cl->request([
    "COMMAND" => "QueryDomainOptions"
    "DOMAIN0" => "example1.com",
    "DOMAIN1" => "example2.com"
]);
echo "<pre>" . htmlspecialchars(print_r($r->getHash(), true)) . "</pre>";
```

#### FYI

`$cl` - the APIClient Object - and `$r` - the Response Object - provide further useful Methods to access configure the connection and to access response data. Have an eye on the [class documentation](https://rawgit.com/hexonet/php-sdk/master/build/api/index.html).

## Contributing

Please read [our development guide](https://github.com/hexonet/php-sdk/wiki/Development-Guide) for details on our code of conduct, and the process for submitting pull requests to us.

## Authors

* **Anthony Schneider** - *development* - [AnthonySchn](https://github.com/anthonyschn)
* **Kai Schwarz** - *development* - [PapaKai](https://github.com/papakai)

See also the list of [contributors](https://github.com/hexonet/php-sdk/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
