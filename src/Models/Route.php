<?php

namespace Dmgctrlr\LaraOsrm\Models;

class Route
{
    protected $routeData;

    public function __construct($routeData)
    {
        $this->routeData = $routeData;
    }

    public function getDistance($unit = 'km', $round = 2)
    {
        if (!isset($this->routeData)) {
            throw new \Exception('I do not have any routeData');
        }
        if (!is_float($this->routeData->distance)) {
            throw new \Exception('I do not have a distance in my route routeData');
        }
        switch (strtolower($unit)) {
            case 'km':
                return round($this->routeData->distance, $round);
            case 'miles':
                return round($this->routeData->distance * 0.6213712, $round);
            default:
                throw new \Exception('Invalid unit passed to getDistance:' . $unit);
        }
    }
}
