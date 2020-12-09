<?php

namespace Dmgctrlr\LaraOsrm\Tests;

use PHPUnit\Framework\TestCase;
use Dmgctrlr\LaraOsrm\RouteServiceRequest;
use Dmgctrlr\LaraOsrm\Models\LatLng;
use Dmgctrlr\LaraOsrm\Responses\RouteServiceResponse;
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


    /**
     * Check that if we make a request which is too long for OSRM
     * we split it into multiple requests and still return happy.
     */
    public function testCanRequestHugeNumbersOfWaypoints()
    {
        $coordinates = [];
        for ($i = 0; $i < 300; $i++) {
            $coordinates[] = new LatLng(51.625226, -0.258437);
        }

        $routeRequest = new RouteServiceRequest($this->getOsrmConfig());
        $routeRequest->setCoordinates($coordinates);
        $this->delayBetweenSending();
        $response = $routeRequest->sendChunk();
        $this->assertEquals('Ok', $response->getStatus());
    }

    /**
     * Make the same request with send and sendChunk and check the results are the same.
     */
    public function testSendChunkReturnsSameAsSend()
    {
        file_put_contents('w:/www/inoria/public/output-normal.json', '');
        file_put_contents('w:/www/inoria/public/output-chunked.json', '');
        $coordinates = [
            new LatLng(51.592862, -0.16597),
            new LatLng(51.592847, -0.165962),
            new LatLng(51.593093, -0.164624),
            new LatLng(51.593422, -0.162832),
            new LatLng(51.593763, -0.160974),
            new LatLng(51.593768, -0.160975),
            new LatLng(51.593083, -0.160708),
            new LatLng(51.593084, -0.160709),
            new LatLng(51.592887, -0.161777),
            new LatLng(51.592641, -0.163102),
            new LatLng(51.592166, -0.165668),
            new LatLng(51.592371, -0.164564),
            new LatLng(51.592756, -0.162485),
            new LatLng(51.592986, -0.161239),
            new LatLng(51.592872, -0.160628),
            new LatLng(51.592581, -0.160518),
            new LatLng(51.592397, -0.160448),
            new LatLng(51.592291, -0.161021),
            new LatLng(51.592072, -0.162206),
            new LatLng(51.591803, -0.163663),
            new LatLng(51.591629, -0.164604),
            new LatLng(51.591924, -0.163008),
            new LatLng(51.592173, -0.161658),
            new LatLng(51.592244, -0.16039),
            new LatLng(51.591934, -0.160271),
            new LatLng(51.591723, -0.160191),
            new LatLng(51.59164, -0.160644),
            new LatLng(51.591486, -0.16147),
            new LatLng(51.591293, -0.162517),
            new LatLng(51.591029, -0.163939),
            new LatLng(51.591175, -0.163151),
            new LatLng(51.591403, -0.161919),
        ];

        $normalRouteRequest = new RouteServiceRequest($this->getOsrmConfig());
        $normalRouteRequest->setOptions(['geometries' => 'geojson'], 'steps');
        $normalRouteRequest->setCoordinates($coordinates);

        $chunkedRouteRequest = new RouteServiceRequest($this->getOsrmConfig());
        $chunkedRouteRequest->setOptions(['geometries' => 'geojson'], 'steps');
        $chunkedRouteRequest->setCoordinates($coordinates);

        /** @var RouteServiceResponse $normalResponse */
        $normalResponse = $normalRouteRequest->send();
        $this->delayBetweenSending();
        /** @var RouteServiceResponse $chunkedResponse */
        $chunkedResponse = $chunkedRouteRequest->sendChunk(9);

        file_put_contents('w:/www/inoria/public/output-normal.json', json_encode($normalResponse->getResponseData()->waypoints, JSON_PRETTY_PRINT));
        file_put_contents('w:/www/inoria/public/output-chunked.json', json_encode($chunkedResponse->getResponseData()->waypoints, JSON_PRETTY_PRINT));

        $this->assertEquals(
            count($normalResponse->getResponseData()->waypoints),
            count($chunkedResponse->getResponseData()->waypoints),
            'count(send()->waypoints) does not match count(sendChunk()->waypoints)'
        );

        $this->assertEquals(
            $normalResponse->getResponseData()->waypoints,
            $chunkedResponse->getResponseData()->waypoints,
            'sendChunk() returned different waypoints points from send()'
        );

        $normalRouteData = $normalResponse->getFirstRoute()->getRouteData();
        $chunkedRouteData = $chunkedResponse->getFirstRoute()->getRouteData();

        file_put_contents('w:/www/inoria/public/output-normal.json', json_encode($normalRouteData, JSON_PRETTY_PRINT));
        file_put_contents('w:/www/inoria/public/output-chunked.json', json_encode($chunkedRouteData, JSON_PRETTY_PRINT));

        $this->assertEquals(
            count($normalRouteData->geometry->coordinates),
            count($chunkedRouteData->geometry->coordinates),
            'sendChunk does not have the same number of geometry points'
        );
        $this->assertEquals(
            $normalRouteData->geometry->coordinates,
            $chunkedRouteData->geometry->coordinates,
            'sendChunk() returned different geometry points from send()'
        );
        $this->assertEquals(
            $normalRouteData->distance,
            $chunkedRouteData->distance,
            'sendChunk is not totalling the distance properly'
        );
        $this->assertEquals(
            $normalRouteData->weight,
            $chunkedRouteData->weight,
            'sendChunk is not totalling the weight properly'
        );
        $this->assertEquals(
            $normalRouteData->duration,
            $chunkedRouteData->duration,
            'sendChunk is not totalling the duration properly'
        );
        $this->assertEquals(
            $normalResponse->getResponseData(),
            $chunkedResponse->getResponseData(),
            'The overall response data did not match'
        );
    }

    /**
     * sendJson only works in limited circumstances currently. Check that we throw useful
     * errors when that happens,
     */
    public function testSendChunkRequirements()
    {
        $routeRequest = new RouteServiceRequest($this->getOsrmConfig());
        $routeRequest->setCoordinates([new LatLng(51.625226, -0.258437),  new LatLng(51.625226, -0.258437)]);
        $this->expectExceptionMessage('sendChunk only works when geometries=geogson');
        $routeRequest->sendChunk();
    }
}
