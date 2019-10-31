<?php

namespace Dmgctrlr\LaraOsrm\Responses;

use Dmgctrlr\LaraOsrm\AbstractResponse;

class RouteServiceResponse extends AbstractResponse
{
    public function getStatus()
    {
        return $this->responseJson->code;
    }

    public function getWaypoints()
    {
        return $this->responseJson->waypoints;
    }

    public function getRoute()
    {
        return $this->responseJson->routes;
    }

    public function getFirstRoute()
    {
        return $this->responseJson->routes[0];
    }
}
