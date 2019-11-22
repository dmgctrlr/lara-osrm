<?php

namespace Dmgctrlr\LaraOsrm\Models;

class Trip
{
    protected $tripData;

    public function __construct(\StdClass $tripData)
    {
        $this->tripData = $tripData;
    }

    /**
     * Access the raw trip data, use this if a method for what you need doesn't
     * exist yet.
     * The returned StdClass is based on this:
     * https://github.com/Project-OSRM/osrm-backend/blob/master/docs/http.md#trip-service
     *
     * @return StdClass
     */
    public function getTripData()
    {
        return $this->tripData;
    }
}
