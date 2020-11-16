# Changelog

All notable changes to `lara-osrm` will be documented in this file

## [UNRELEASED]
 - Removed unnecessary dependency orchestra/testbench
## 1.0 2019-11

- Breaking: Pass LatLng objects, not arrays to set coordinated.
- Breaking: $request->send() returns a Response object, not a Guzzle object.
- Breaking: Only Route and Trip (TSP) service has been refactored - others don't work.
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

## 0.1.0 and lower.

 - See commits.