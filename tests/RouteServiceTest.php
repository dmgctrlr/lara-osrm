<?php

namespace Dmgctrlr\LaraOsrm\Tests;

use PHPUnit\Framework\TestCase;
use Dmgctrlr\LaraOsrm\RouteServiceRequest;
use Dmgctrlr\LaraOsrm\Models\LatLng;
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
            new LatLng(52.537307, 13.428395),
            new LatLng(52.509420, 13.452084),
            new LatLng(52.51171, 13.401272),
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
            new LatLng(52.537307, 13.428395),
        ]);
        try {
            $this->delayBetweenSending();
            $response = $routeRequest->send();
        } catch (ClientException $e) {
            $this->assertEquals(
                400,
                $e->getResponse()->getStatusCode()
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
            new LatLng(52.5152238, 13.4590997),
            new LatLng(52.5098546, 13.5412887),
        ]);
        $this->delayBetweenSending();
        $response = $routeRequest->send();
        /* @var $route \Dmgctrlr\LaraOsrm\Models\Route */
        $route = $response->getFirstRoute();
        $this->assertEquals(
            '5621.50',
            $route->getDistance(),
            'Get the distance in meters'
        );
        $this->assertEquals(
            '5.62',
            $route->getDistance('km'),
            'Get the distance in kilometers'
        );
        $this->assertEquals(
            '3.49',
            $route->getDistance('miles'),
            'Get the same distance in miles'
        );
        $this->assertEquals(
            '3.493',
            $route->getDistance('miles', 3),
            'Get the same distance in miles with 3 points of precision'
        );
    }
}
