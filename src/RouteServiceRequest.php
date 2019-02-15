<?php

namespace Dmgctrlr\LaraOsrm;

class RouteServiceRequest extends BaseServiceRequest
{
    /**
     * Specifies the name of the service passed in the URL
     * @var string
     */
    public $service = 'route';

    /**
     * These are the default OSRM parameters for the route service
     * @var array
     */
    public $options = [
        'alternatives' => 'false',
        'steps'        => 'false',
        'annotations'  => 'false',
        'geometries'   => 'polyline', // polyline, polyline6 or geojson
        'overview'     => 'simplified', // simplified, full or false
    ];

    /**
     * if creating a new response object type
     * ???
     * over ride the send method? guzzlehttp response?
     */

}
