<?php

namespace Dmgctrlr\LaraOsrm\Responses;

use Dmgctrlr\LaraOsrm\AbstractResponse;
use Dmgctrlr\LaraOsrm\Models\Route;

class MatchServiceResponse extends AbstractResponse
{
    public function getWaypoints()
    {
        return $this->responseData->waypoints;
    }

    public function getMatchings()
    {
        if (!isset($this->routes)) {
            $this->routes = [];
            foreach ($this->responseData->routes as $route) {
                $this->routes[] = new Route($route);
            }
        }
        return $this->routes;
    }

    /**
     * An alias for getMatchings() so we can be consistent in using
     * getRoutes() but also consistent with OSRM's terminology.
     *
     **/
    public function getRoutes()
    {
        return $this->getMatchings();
    }
}
