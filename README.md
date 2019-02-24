# BaseCardHero Sheets

[![Latest Version on Packagist](https://img.shields.io/packagist/v/basecardhero/sheets.svg?style=flat-square)](https://packagist.org/packages/basecardhero/sheets)
[![Latest Unstable Version](https://poser.pugx.org/basecardhero/sheets/v/unstable)](https://packagist.org/packages/basecardhero/sheets)
[![Build Status](https://img.shields.io/travis/basecardhero/sheets/master.svg?style=flat-square)](https://travis-ci.org/basecardhero/sheets)
[![codecov](https://codecov.io/gh/basecardhero/sheets/branch/master/graph/badge.svg)](https://codecov.io/gh/basecardhero/sheets)
[![License](https://poser.pugx.org/basecardhero/sheets/license)](https://packagist.org/packages/basecardhero/sheets)
[![composer.lock](https://poser.pugx.org/basecardhero/sheets/composerlock)](https://packagist.org/packages/basecardhero/sheets)
[![Total Downloads](https://img.shields.io/packagist/dt/basecardhero/sheets.svg?style=flat-square)](https://packagist.org/packages/basecardhero/sheets)

_This package was created for a project I am working on and does not fully support Google services. Feel free to add functionality by creating a pull request. See [contributing](CONTRIBUTING.md)._

## Installation

You can install the package via [composer](https://getcomposer.org/):

``` bash
$ composer require basecardhero/sheets
```

## Usage

You will need to install the [Google Client](https://github.com/googleapis/google-api-php-client) library and configure an application for it. See [gsuitedevs/php-samples](https://github.com/gsuitedevs/php-samples) for examples configuring a Google Client for php.

### Examples

#### Create a Google Sheet

``` php
require_once '/project/path/vendor/autoload.php';

// Configure your Google client.
$client = new \Google_Client();
$sheetService = new \Google_Service_Sheets($client);
$sheets = new \BaseCardHero\Sheets\Sheets($sheetService);

$sheetId = $sheets->create('My Sheet Title');

echo $sheetId; // pq938fnqp9348fqp948fq3p948fhqp349fh
```

### Testing

``` bash
$ composer all
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email ryan@basecardhero.com instead of using the issue tracker.

## Credits

- [Base Card Hero](https://github.com/basecardhero)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
