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
        if (!isset($this->routeData)) {
            throw new \Exception('I do not have any routeData');
        }
        if (!is_int($this->routeData->distance)) {
            throw new \Exception('I do not have a distance in my route routeData');
        }
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
