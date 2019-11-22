<?php

namespace Dmgctrlr\LaraOsrm\Tests;

use PHPUnit\Framework\TestCase;
use Dmgctrlr\LaraOsrm\TripServiceRequest;
use Dmgctrlr\LaraOsrm\Models\LatLng;
use GuzzleHttp\Exception\ClientException;

class TripServiceTest extends TestCase
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
            'host' => env('OSRM_HOST', 'tripr.project-osrm.org'),
            'port' => env('OSRM_PORT', '80'),
        ];
    }
    /**
     * Check we can make a request, and get an Ok back.
     */
    public function testCanRequestTrip()
    {
        $request = new TripServiceRequest($this->getOsrmConfig());
        $request->setCoordinates([
            new LatLng(52.537307, 13.428395),
            new LatLng(52.509420, 13.452084),
            new LatLng(52.51171, 13.401272),
        ]);
        $this->delayBetweenSending();
        $response = $request->send();
        $this->assertEquals('Ok', $response->getStatus());
    }

    /**
     * Check that if we make an invalid request (a trip request with only
     * one waypoint) get get an error.
     */
    public function testGetAnErrorOnInvalidRequest()
    {
        $request = new TripServiceRequest($this->getOsrmConfig());
        $request->setCoordinates([
            new LatLng(52.537307, 13.428395),
        ]);

        try {
            $this->delayBetweenSending();
            $response = $request->send();
        } catch (ClientException $e) {
            $this->assertEquals(
                400,
                $e->getResponse()->getStatusCode()
            );
        }
    }
}
