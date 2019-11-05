# Wrapper around OSRM for Laravel

[![Build Status](https://img.shields.io/travis/com/dmgctrlr/lara-osrm/master.svg?style=flat-square)](https://travis-ci.com/dmgctrlr/lara-osrm)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/dmgctrlr/lara-osrm.svg?style=flat-square)](https://packagist.org/packages/dmgctrlr/lara-osrm)
![PHP Version Minimum](https://img.shields.io/packagist/php-v/dmgctrlr/lara-osrm.svg?style=flat-square)
[![License](https://img.shields.io/packagist/l/dmgctrlr/lara-osrm.svg?style=flat-square)](https://packagist.org/packages/dmgctrlr/lara-osrm)

This package is a simple wrapper for querying OSRM. It assumes you have an OSRM v5.x server available. It supports route, nearest, table, match, and trip services. Driving is the only profile currently supported.

## Installation

You can install the package via composer:

```bash
composer require dmgctrlr/lara-osrm
```

Publish the config file (config/lara-osrm.config)

```bash
php artisan vendor:publish --tag="config" --provider="Dmgctrlr\LaraOsrm\LaraOsrmServiceProvider"
```

You can overwrite the defaults in your `.env` file too:
```dotenv
OSRM_HOST=localhost
OSRM_PORT=5000
OSRM_VERSION=v1
```

## Usage

## Route Calculation
``` php

use Dmgctrlr\LaraOsrm\RouteServiceRequest;
use Dmgctrlr\LaraOsrm\Models\LatLng;

$request = new RouteServiceRequest();
$request->setCoordinates([
    new LatLng(33.712053, -112.068195),
    new LatLng(33.602053, -112.065295),
    new LatLng(33.626367, -112.023641)
]);
// `send()` returns a Dmgctrlr\LaraOsrm\Responses\RouteServiceResponse (since we made a RouteServiceRequest).
$response = $request->send();
$status = $response->getStatus(); // "Ok"
$status = $response->getMessage(); // Mostly useful for getting the error message if there's a problem.

$routes = $response->getRoutes(); // Returns an array of Dmgctrlr\LaraOsrm\Models\Route

// @var Dmgctrlr\LaraOsrm\Models\Route $recommendedRoute **/
$recommendedRoute = $response->getFirstRoute(); // Returns the first/primary route
$recommendedRoute->getDistance(); // Returns in meters
$recommendedRoute->getDistance('km'); // Returns in kilometers
$recommendedRoute->getDistance('miles', 4); // Returns in milesr ronded to 4 decimal places

// you can override the default options for each supported service
$response = LaraOsrmFacade::drivingDistance()
    ->setCoordinates([
        new LatLng(33.712053, -112.068195),
        new LatLng(33.602053, -112.065295),
        new LatLng(33.626367, -112.023641)
    ])
    ->setOptions('steps', 'annotations', ['overview' => 'full'], ['geometries' => 'geojson'])
    ->send();
```

### Testing

If you get errors like `Failed asserting that 429 matches expected 400.` or other mentions of
code 429 then the server you're using is probably busy (or rate limiting you).

By default the tests will use the OSRM demo server, to use your own or another
server `cp phpunit.xml.dist phpunit.xml` and edit the environment variables.

If you're getting errors about a route not having a distance, and you're using
your own server - check the server is properly configured and is not returning
non-zero distances. For these tests you must have the "berlin" area installed.

``` bash
composer test
```

or (better on Windows):

```bash
vendor/bin/phpunit
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
