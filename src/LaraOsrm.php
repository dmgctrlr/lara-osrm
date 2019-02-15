<?php

namespace Dmgctrlr\LaraOsrm;

class LaraOsrm
{
    // see http://project-osrm.org/docs/v5.5.1/api/?language=cURL#nearest-service

    /**
     * Finds the fastest route between coordinates in the supplied order.
     *
     * @return RouteServiceRequest
     */
    public function drivingDistance()
    {
        return new RouteServiceRequest();
    }

    /**
     * Solves the Traveling Salesman Problem using a greedy heuristic
     *
     * @return TripServiceRequest
     */
    public function tsp()
    {
        return new TripServiceRequest();
    }

    /**
     * Snaps a coordinate to the street network and returns the nearest n matches.
     *
     * @return NearestServiceRequest
     */
    public function nearest()
    {
        return new NearestServiceRequest();
    }

    /**
     * Computes the duration of the fastest route between all pairs of supplied coordinates.
     *
     * @return TableServiceRequest
     */
    public function table()
    {
        return new TableServiceRequest();
    }

    /**
     * Map matching matches/snaps given GPS points to the road network in the most plausible way.
     *
     * @return MatchServiceRequest
     */
    public function match()
    {
        return new MatchServiceRequest();
    }

}
