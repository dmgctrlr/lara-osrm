<?php
/**
 * Created by PhpStorm.
 * User: dmg
 * Date: 2/13/19
 * Time: 5:27 PM
 */

namespace Dmgctrlr\LaraOsrm;


class TableServiceRequest
{
    /**
     * Specifies the name of the service passed in the URL
     * @var string
     */
    public $service = 'table';

    /**
     * These are the default OSRM parameters for the match service
     * @var array
     */
    public $options = [
        'sources'       => [],
        'destinations'  => []
    ];

}
