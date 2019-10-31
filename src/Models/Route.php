<?php

namespace Dmgctrlr\LaraOsrm\Models;

class Route
{
    protected $routeData;

    public function __construct($routeData)
    {
        $this->routeData = $routeData;
    }
}
