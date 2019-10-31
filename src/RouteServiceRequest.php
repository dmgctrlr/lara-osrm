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
    public $options = [];
}
