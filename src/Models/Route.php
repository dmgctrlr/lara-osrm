<?php

namespace Dmgctrlr\LaraOsrm\Models;

class Route
{
    protected $routeData;

    public function __construct(StdObj $routeData)
    {
        $this->routeData = $routeData;
    }

    /**
     * Access the raw route data, use this if a method for what you need doesn't
     * exist yet.
     * The returned StdObject is based on this:
     * https://github.com/Project-OSRM/osrm-backend/blob/master/docs/http.md
     *
     * @return StdObject
     */
    public function getRouteData()
    {
        return $this->routeData;
    }

    /**
     * Get the total distance of this route in meters rounded to 2 decimal places.
     * Optionally request the distance in km or miles and with different roundings.
     *
     * @param string $unit meters|km|miles
     * @param integer $round
     * @return void
     */
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
