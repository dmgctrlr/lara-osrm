<?php

namespace Dmgctrlr\LaraOsrm\Responses;

use Dmgctrlr\LaraOsrm\AbstractResponse;
use Dmgctrlr\LaraOsrm\Models\Route;

class RouteServiceResponse extends AbstractResponse
{
    private $routes;
    const STATUS_NO_ROUTE = 'NoRoute'; // No route found.

    public function getWaypoints()
    {
        return $this->responseData->waypoints;
    }

    public function getRoutes()
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
     * Get the "first" route if there are multiple. This should be the
     * most recommended route (unless you've specified some other options).
     *
     * @return \Dmgctrlr\LaraOsrm\Models\Route;
     */
    public function getFirstRoute()
    {
        $routes = $this->getRoutes();
        if (isset($routes[0]) && $routes[0] instanceof \Dmgctrlr\LaraOsrm\Models\Route) {
            return $routes[0];
        }
        throw new \Exception('No routes found');
    }
}
