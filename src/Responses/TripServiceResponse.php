<?php

namespace Dmgctrlr\LaraOsrm\Responses;

use Dmgctrlr\LaraOsrm\AbstractResponse;
use Dmgctrlr\LaraOsrm\Models\Trip;

class TripServiceResponse extends AbstractResponse
{
    private $trips;
    const STATUS_NO_TRIP = 'NoTrip'; // No trip found.
    const STATUS_NOT_IMPLEMENTED = 'NotImplemented'; // This request is not supported

    public function getWaypoints()
    {
        return $this->responseData->waypoints;
    }

    public function getTrips()
    {
        if (!isset($this->trips)) {
            $this->trips = [];
            foreach ($this->responseData->trips as $trip) {
                $this->trips[] = new Trip($trip);
            }
        }
        return $this->trips;
    }

    /**
     * Get the "first" trip if there are multiple. This should be the
     * most recommended trip (unless you've specified some other options).
     *
     * @return \Dmgctrlr\LaraOsrm\Models\Trip;
     */
    public function getFirstTrip()
    {
        $trips = $this->getTrips();
        if (isset($trips[0]) && $trips[0] instanceof \Dmgctrlr\LaraOsrm\Models\Trip) {
            return $trips[0];
        }
        throw new \Exception('No trips found');
    }
}
