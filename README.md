# BaseCardHero - Spreadsheet

[![Build Status](https://img.shields.io/travis/basecardhero/spreadsheet/master.svg)](https://travis-ci.org/basecardhero/spreadsheet)
[![codecov](https://codecov.io/gh/basecardhero/spreadsheet/branch/master/graph/badge.svg)](https://codecov.io/gh/basecardhero/spreadsheet)
[![License](https://poser.pugx.org/basecardhero/spreadsheet/license?c=0)](https://packagist.org/packages/basecardhero/spreadsheet)
[![composer.lock](https://poser.pugx.org/basecardhero/spreadsheet/composerlock)](https://packagist.org/packages/basecardhero/spreadsheet)

_This package was created for a project I am working on and does not fully support Google services (or the way you may want it to). Feel free to add functionality by creating a pull request. See [contributing](CONTRIBUTING.md)._

## Installation

You can install the package via [composer](https://getcomposer.org/):

``` bash
$ composer require basecardhero/spreadsheet
```

## Usage

You will need to configure the [Google Client](https://github.com/googleapis/google-api-php-client). See [gsuitedevs/php-samples](https://github.com/gsuitedevs/php-samples) for examples configuring a Google Client for php.

## Examples

### Create a Spreadsheet instance

``` php
require_once '/project/path/vendor/autoload.php';


$client = new \Google_Client(); // Make sure to configure your Google client.
$sheetService = new \Google_Service_Sheets($client);
$spreadsheet = new \BaseCardHero\Spreadsheet\Spreadsheet($sheetService);
```

### Create a spreadsheet

The `create()` method will fetch a new spreadsheet from the rest service.

``` php
$spreadsheet->create()
    ->getSpreadsheet(); // \Google_Service_Sheets_Spreadsheet
```

### Retrieve a spreadsheet

The `retrieve()` method will fetch an existing spreadsheet from the rest service.

``` php
$spreadsheetId = '1b7c48b64ef1d5bf093632e7f8aa6529';

$spreadsheet->retrieve($spreadsheetId)
    ->getSpreadsheet(); // \Google_Service_Sheets_Spreadsheet
```

### Set the title

``` php
$spreadsheet->create()
    ->setTitle('My Spreadsheet Title')
    ->getSpreadsheet(); // \Google_Service_Sheets_Spreadsheet
```

### Set column data

``` php
$columns = [
    [
        'range' => 'A1:A',
        'values' => [0, 1, 2, 3]
    ],
    [
        'range' => 'B1:B',
        'values' => ['a', 'b', 'c', 'd']
    ],
];

$spreadsheet->create()
    ->setColumns($columns)
    ->getSpreadsheet(); // \Google_Service_Sheets_Spreadsheet
```

### Clearing data on a spreadsheet

``` php
$spreadsheet->create()
    ->clearRanges(['A1:A', 'B1:B'])
    ->getSpreadsheet(); // \Google_Service_Sheets_Spreadsheet
```

### Copying a sheet from another spreadsheet

``` php
$otherSpreadsheetId = '635d0d664ff92db666a9be5ed84f231c';
$otherSheetId = 0;

$spreadsheet->create()
    ->copySheetFrom($otherSpreadsheetId, $otherSheetId)
    ->getSpreadsheet(); // \Google_Service_Sheets_Spreadsheet
```

### Delete a sheet within the spreadsheet

``` php
$sheetId = 0;

$spreadsheet->create()
    ->deleteSheet($sheetId)
    ->getSpreadsheet(); // \Google_Service_Sheets_Spreadsheet
```

## Testing

``` bash
$ composer all
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email ryan@basecardhero.com instead of using the issue tracker.

## Credits

- [Base Card Hero](https://github.com/basecardhero) | [basecardhero.com](https://basecardhero.com/)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## PHP Package Boilerplate

This package was generated using the [PHP Package Boilerplate](https://laravelpackageboilerplate.com).
