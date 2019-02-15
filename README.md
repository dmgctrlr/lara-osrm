# Wrapper around OSRM for Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dmgctrlr/lara-osrm.svg?style=flat-square)](https://packagist.org/packages/dmgctrlr/lara-osrm)
[![Build Status](https://img.shields.io/travis/dmgctrlr/lara-osrm/master.svg?style=flat-square)](https://travis-ci.org/dmgctrlr/lara-osrm)
[![Total Downloads](https://img.shields.io/packagist/dt/dmgctrlr/lara-osrm.svg?style=flat-square)](https://packagist.org/packages/dmgctrlr/lara-osrm)

This package is a simple wrapper for querying OSRM. It assumes you have an OSRM v5.x server available. It supports route, nearest, table, match, and trip services. Driving is the only profile currently supported.

## Installation

You can install the package via composer:

```bash
composer require dmgctrlr/lara-osrm
```

## Usage

``` php
$response = LaraOsrmFacade::drivingDistance()
    ->setCoordinates([
        [-112.068195, 33.712053],
        [-112.065295, 33.602053],
        [-112.023641, 33.626367]
    ])->send();

// you can override the default options for each supported service
$response = LaraOsrmFacade::drivingDistance()
    ->setCoordinates([
        [-112.068195, 33.712053],
        [-112.065295, 33.602053],
        [-112.023641, 33.626367]
    ])
    ->setOptions('steps', 'annotations', ['overview' => 'full'], ['geometries' => 'geojson'])
    ->send();
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email dm@mediavariance.com instead of using the issue tracker.

## Credits

- [Daniel](https://github.com/dmgctrlr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
