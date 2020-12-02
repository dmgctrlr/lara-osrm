<?php
/**
 * Created by PhpStorm.
 * User: dmg
 * Date: 2/13/19
 * Time: 5:28 PM
 */

namespace Dmgctrlr\LaraOsrm;


class MatchServiceRequest extends BaseServiceRequest
{
    /**
     * Specifies the name of the service passed in the URL
     * see http://project-osrm.org/docs/v5.5.1/api/#match-service
     * @var string
     */
    public $service = 'match';

    /**
     * These are the default OSRM parameters for the match service
     * @var array
     */
    public $options = [
        'steps'       => null,
        'geometries'  => null, // polyline, polyline6 or geojson
        'annotations' => null,
        'overview'    => null, // simplified, full or false
        'annotations'  => null,
        'radiuses'    => null
    ];

    public function setRadiuses(...$options): parent
    {
        $this->options['radiuses'] = implode(';', $options[0] );

        return $this;
    }

}
