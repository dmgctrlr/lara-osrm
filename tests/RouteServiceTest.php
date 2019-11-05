<?php

namespace Dmgctrlr\LaraOsrm\Tests;

use PHPUnit\Framework\TestCase;
use Dmgctrlr\LaraOsrm\RouteServiceRequest;
use GuzzleHttp\Exception\ClientException;

class RouteServiceTest extends TestCase
{
    /**
     * In order to be able to test against the demo server, we need to
     * wait a bit between requests. We don't need to finish our test fasts
     * but we do need them to be reliable.
     *
     * @return void
     */
    private function delayBetweenSending()
    {
        sleep(5);
    }

    private function getOsrmConfig()
    {
        return [
            'host' => env('OSRM_HOST', 'router.project-osrm.org'),
            'port' => env('OSRM_PORT', '80'),
        ];
    }
    /**
     * Check we can make a request, and get an Ok back.
     */
    public function testCanGetDrivingDirections()
    {
        $routeRequest = new RouteServiceRequest($this->getOsrmConfig());
        $routeRequest->setCoordinates([
            [13.428395, 52.537307],
            [13.452084, 52.509420],
            [13.401272, 52.511718]
        ]);
        $this->delayBetweenSending();
        $response = $routeRequest->send();
        $this->assertEquals('Ok', $response->getStatus());
    }

    /**
     * Check that if we make an invalid request (a route request with only
     * one waypoint) get get an error.
     */
    public function testGetAnErrorOnInvalidRequest()
    {
        $routeRequest = new RouteServiceRequest($this->getOsrmConfig());
        $routeRequest->setCoordinates([
            [13.428395, 52.537307],
        ]);
        try {
            $this->delayBetweenSending();
            $response = $routeRequest->send();
        } catch (ClientException $e) {
            $this->assertEquals(
                $e->getResponse()->getStatusCode(),
                400
            );
        }
    }

    /**
     * Check that if we make an invalid request (a route request with only
     * one waypoint) get get an error.
     */
    public function testGettingDistanceInDifferentUnits()
    {
        $routeRequest = new RouteServiceRequest($this->getOsrmConfig());
        $routeRequest->setCoordinates([
            [13.358583, 52.514707],
            [13.451166, 52.509881],
        ]);
        $this->delayBetweenSending();
        $response = $routeRequest->send();
        /* @var $route \Dmgctrlr\LaraOsrm\Models\Route */
        $route = $response->getFirstRoute();
        $this->assertEquals(
            '11',
            $route->getDistance(),
            'Get the distance in the default unit (km)'
        );
        $this->assertEquals(
            '12',
            $route->getDistance('miles'),
            'Get the same distance in miles'
        );
    }
}
