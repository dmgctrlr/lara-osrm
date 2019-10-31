<?php

namespace Dmgctrlr\LaraOsrm\Tests;

use PHPUnit\Framework\TestCase;
use Dmgctrlr\LaraOsrm\RouteServiceRequest;
use GuzzleHttp\Exception\ClientException;

class RouteServiceTest extends TestCase
{
    /**
     * Check we can make a request, and get an Ok back.
     */
    public function testCanGetDrivingDirections()
    {
        $routeRequest = new RouteServiceRequest([
            'host' => 'router.project-osrm.org',
            'port' => 80
        ]);
        $routeRequest->setCoordinates([
            [-112.068195, 33.712053],
            [-112.065295, 33.602053],
            [-112.023641, 33.626367]
        ]);
        $response = $routeRequest->send();
        $this->assertEquals('Ok', $response->getStatus());
    }

    /**
     * Check that if we make an invalid request (a route request with only
     * one waypoint) get get an error.
     */
    public function testGetAnErrorOnInvalidRequest()
    {
        $routeRequest = new RouteServiceRequest([
            'host' => 'router.project-osrm.org',
            'port' => 80
        ]);
        $routeRequest->setCoordinates([
            [-112.068195, 33.712053]
        ]);
        try {
            $response = $routeRequest->send();
        } catch (ClientException $e) {
            $this->assertEquals(
                $e->getResponse()->getStatusCode(),
                422
            );
        }
    }
}
