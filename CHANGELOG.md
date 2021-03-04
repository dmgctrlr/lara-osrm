# Changelog

All notable changes to `lara-osrm` will be documented in this file

# Unreleased Changes

 - None
## 2.1 2021-03-04

 - Add `sendChunk()` method to enable huge requests

## 2.0 2020-12-02

Version 2 brings the library up to date with the latest dependencies, adds a few minor features,
fixes a couple of bugs and adds a couple of tests.

I've merged in a couple of previous "releases" (1.1 and 1.2) into this version 2
because I didn't release them properly and they weren't fully backwards compatible with v1.
So the file history here may look a bit odd - but just ignore any versions >1 and <2.

- Dropped support for PHP < 7.3
- Removed no-longer-working CodeCoverage scripts from travis.yml
- Reintroduced $options array on requests (fixing the setOptions() method)
- Defaulted options to null - so the server default will be used rather than this package versions.
- Removed 'timestamps' from MatchServiecRequest (doesn't appear to be supported as an option on OSRM)
- Added 'annotations' option to MatchServiceRequest
- Added tests for URL Generation
- Added MatchServiceRequest to enable Match Requests.
- Added Laravel 7 and 8 compatibility
- Update Illuminate/Support, Guzzle, TestBench and PHP Unit to the latest versions.

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