<?php
/**
 * Created by PhpStorm.
 * User: dmg
 * Date: 2/13/19
 * Time: 5:27 PM
 */

namespace Dmgctrlr\LaraOsrm;


class NearestServiceRequest extends BaseServiceRequest
{
    /**
     * Specifies the name of the service passed in the URL
     * @var string
     */
    public $service = 'nearest';

    /**
     * These are the default OSRM parameters for the nearest service
     * @var array
     */
    public $options = [
        'number' => 1
    ];

    public function setCoordinates(array $coordinates): BaseServiceRequest {
        // nearest can only have 1, so wrap the lat/lon
        $this->coordinates = [$coordinates];
        return $this;
    }

}
