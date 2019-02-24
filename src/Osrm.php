<?php

namespace Dmgctrlr\LaraOsrm;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dmgctrlr\LaraOsrm\Skeleton\SkeletonClass
 */
class Osrm extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'lara-osrm';
    }
}
