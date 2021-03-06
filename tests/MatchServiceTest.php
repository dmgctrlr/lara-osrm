<?php

namespace Dmgctrlr\LaraOsrm\Tests;

use PHPUnit\Framework\TestCase;
use Dmgctrlr\LaraOsrm\MatchServiceRequest;
use Dmgctrlr\LaraOsrm\Models\LatLng;
use GuzzleHttp\Exception\ClientException;

class MatchServiceTest extends TestCase
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
    public function testCanMatchWaypoints()
    {
        $matchRequest = new MatchServiceRequest($this->getOsrmConfig());
        $matchRequest->setCoordinates([
            new LatLng(51.618060, -0.239197),
            new LatLng(51.617594, -0.239781),
        ]);
        $this->delayBetweenSending();
        $response = $matchRequest->send();
        $this->assertEquals('Ok', $response->getStatus());
    }

    /**
     * Check that if we make an invalid request (a route request with only
     * one waypoint) get get an error.
     */
    public function testGetAnErrorOnInvalidMatchRequest()
    {
        $matchRequest = new MatchServiceRequest($this->getOsrmConfig());
        $matchRequest->setCoordinates([
            new LatLng(52.537307, 13.428395),
        ]);
        try {
            $this->delayBetweenSending();
            $response = $matchRequest->send();
        } catch (ClientException $e) {
            $this->assertEquals(
                400,
                $e->getResponse()->getStatusCode()
            );
            return true;
        }
        $this->assertFalse(true, 'MatchServiceRequest did not throw an error when we sent an invalid query.');
    }

    /**
     * Check we can make a request, and get an Ok back.
     */
    public function testUrlGeneration()
    {
        $matchRequest = new MatchServiceRequest($this->getOsrmConfig());
        $matchRequest->setCoordinates([
            new LatLng(51.618060, -0.239197),
            new LatLng(51.617594, -0.239781),
        ]);
        $this->assertEquals(
            'router.project-osrm.org:80/match/v1/driving/-0.239197,51.61806;-0.239781,51.617594',
            $matchRequest->getUrl()
        );

        $matchRequest->addOption('steps');
        $this->assertEquals(
            'router.project-osrm.org:80/match/v1/driving/-0.239197,51.61806;-0.239781,51.617594?steps=true',
            $matchRequest->getUrl(),
            'adding a simple true/false setting'
        );

        $matchRequest->addOption(['steps' => null]);
        $this->assertEquals(
            'router.project-osrm.org:80/match/v1/driving/-0.239197,51.61806;-0.239781,51.617594',
            $matchRequest->getUrl(),
            'remove a true/false setting'
        );

        // Change the coordinates
        $matchRequest->setCoordinates([
            new LatLng(52.618060, -11.238197),
            new LatLng(52.617594, -11.238781),
        ]);
        $matchRequest->addOption(['steps' => null]);
        $this->assertEquals(
            'router.project-osrm.org:80/match/v1/driving/-11.238197,52.61806;-11.238781,52.617594',
            $matchRequest->getUrl(),
            'changing the coordinates'
        );

        $matchRequest->setOptions(
            'steps', // This should add steps=true
            ['overview' => 'simplified'], // This should add overview=simplified
        );

        $this->assertEquals(
            'router.project-osrm.org:80/match/v1/driving/-11.238197,52.61806;-11.238781,52.617594?steps=true&overview=simplified',
            $matchRequest->getUrl(),
            'set multiple options'
        );

    }
}
