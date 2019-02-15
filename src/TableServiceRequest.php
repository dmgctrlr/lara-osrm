<?php
/**
 * Created by PhpStorm.
 * User: dmg
 * Date: 2/13/19
 * Time: 5:27 PM
 */

namespace Dmgctrlr\LaraOsrm;


class TableServiceRequest extends BaseServiceRequest
{
    /**
     * Specifies the name of the service passed in the URL
     * @var string
     */
    public $service = 'table';

    /**
     * These are the default OSRM parameters for the match service
     * see http://project-osrm.org/docs/v5.5.1/api/#table-service
     * @var array
     */
    public $options = [
        'sources'       => [], // array of coordinates indexes to consider as start points
        'destinations'  => [], // array of coordinates indexes to consider as end points
    ];

    public function setOptions(...$options): parent
    {

    }

}
