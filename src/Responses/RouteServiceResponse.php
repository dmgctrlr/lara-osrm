<?php

namespace Dmgctrlr\LaraOsrm\Responses;

use Dmgctrlr\LaraOsrm\AbstractResponse;

class RouteServiceResponse extends AbstractResponse
{
    private $routes;

    public function getStatus()
    {
        return $this->responseJson->code;
    }

    public function getWaypoints()
    {
        return $this->responseJson->waypoints;
    }

    public function getRoutes()
    {
        if (!isset($this->routes)) {
            $routes = [];
            foreach ($this->responseJson->routes as $route) {
                $routes[] = new Route($route);
            }
            $this->routes = $routes;
        }
        return $this->routes;
    }

    public function getFirstRoute()
    {
        $routes = $this->getRoutes();
        if (isset($routes[0])) {
            $this->getRoute()[0];
        }
        throw new \Exception('No routes found');
    }
}
