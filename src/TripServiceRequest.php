<?php

namespace Dmgctrlr\LaraOsrm;

class TripServiceRequest extends BaseServiceRequest
{
    /**
     * Specifies the name of the service passed in the URL
     * @var string
     */
    public $service = 'trip';

    /**
     * These are the default OSRM parameters for the trip service
     * @var array
     */
    public $options = [
        'steps'        => 'false',
        'annotations'  => 'false',
        'geometries'   => 'polyline', // polyline, polyline6 or geojson
        'overview'     => 'simplified', // simplified, full or false
    ];

}
