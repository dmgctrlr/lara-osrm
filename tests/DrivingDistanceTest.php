<?php

namespace Dmgctrlr\LaraOsrm\Tests;

use PHPUnit\Framework\TestCase;
use Dmgctrlr\LaraOsrm\RouteServiceRequest;

class DrivingDistanceTest extends TestCase
{

    /** @test */
    public function canGetDrivingDirections()
    {
        $routeRequest = new RouteServiceRequest([
            'host' => 'router.project-osrm.org',
            'port' => 80
        ]);
        $response = $routeRequest->setCoordinates([
            [-112.068195, 33.712053],
            [-112.065295, 33.602053],
            [-112.023641, 33.626367]
        ])->send();
        var_dump($response->getFirstRoute());
    }
}
