# Changelog

All notable changes to `lara-osrm` will be documented in this file

## Unreleased Changes

- Added support for Laravel 6
- Added first tests
- Began abstracting Responses (instead of returning the Guzzle\Response)
- Added default settings (OSRM Demo) if Laravel settings are not set
- Added ability to override settings on object creation
- Fixed example code in README.md
- Don't set defaults if they don't need to be set (let OSRM Server handle defaults)
- Removed invalid attributes from phpunit.xml.dist
- Replaced using arrays for coordinates with LatLng object
- Added getDistance() method to Route model
- Added Demo server for running tests, with option to override
- Added Status Code constants and methods for error checking
- A few other bits of refactoring.

## 1.0.0 - 201X-XX-XX

- initial release
