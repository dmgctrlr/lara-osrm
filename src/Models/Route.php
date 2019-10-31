<?php

namespace Dmgctrlr\LaraOsrm\Models;

class Route
{
    protected $routeData;

    public function __construct($routeData)
    {
        $this->routeData = $routeData;
    }

    public function getDistance($unit = 'km')
    {
        switch (strtolower($unit)) {
            case 'km':
                return $this->routeData->distance;
            case 'miles':
                return $this->routeData->distance * 0.6213712;
            default:
                throw new \Exception('Invalid unit passed to getDistance:' . $unit);
        }
    }
}
