<?php

namespace Dmgctrlr\LaraOsrm\Models;

class Route
{
    protected $routeData;

    public function __construct($routeData)
    {
        $this->routeData = $routeData;
    }

    public function getDistance($unit = 'meters', $round = 2)
    {
        if (!isset($this->routeData)) {
            throw new \Exception('I do not have any routeData');
        }
        if (!is_float($this->routeData->distance)) {
            throw new \Exception('I do not have a distance in my route routeData');
        }
        switch (strtolower($unit)) {
            case 'meters':
                return round($this->routeData->distance, $round);
            case 'km':
                // Convert meters to kilometers
                return round($this->routeData->distance / 1000, $round);
            case 'miles':
                // Convers meters to miles
                return round($this->routeData->distance * 0.0006213712, $round);
            default:
                throw new \Exception('Invalid unit passed to getDistance:' . $unit);
        }
    }
}
