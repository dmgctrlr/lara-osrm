<?php

namespace Dmgctrlr\LaraOsrm;

class BaseServiceRequest extends AbstractRequest
{
    protected $version = 'v1';
    protected $profile = 'driving';
    protected $format = 'json';

    public $service; // delegated to extending classes
    public $options; // delegated to extending classes
    public $coordinates; // all extending classes must set coordinates (some accept only one (nearest))

    /**
     * @param array $coordinates
     * @return BaseServiceRequest
     */
    public function setCoordinates(array $coordinates): self
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    /**
     * @param mixed ...$options
     * @return BaseServiceRequest
     */
    public function setOptions(...$options): self
    {
        // loop over the options and add them if supported by the service
        foreach ($options as $option) {
            if (is_array($option)) {
                if (!isset($this->options[array_keys($option)[0]])) {
                    continue; // option not supported
                }
                $this->options[array_keys($option)[0]] = array_values($option)[0];
            } else {
                if (!isset($this->options[$option])) {
                    continue; // option not supported
                }
                $this->options[$option] = 'true';
            }
        }

        return $this;
    }

    /**
     * Add the coordinates
     * @return string
     */
    public function buildCoordinatesURL(): string
    {
        $coordinates = [];
        foreach ($this->coordinates as $coordinate) {
            $coordinates[] = "$coordinate[0],$coordinate[1]";
        }

        return implode(';', $coordinates);
    }

    /**
     * Add available options
     * @return string
     */
    public function buildOptionsURL(): string
    {
        // set to query_string the array of options
        return http_build_query($this->options); // todo test null/false etc
    }

    /**
     * make a standard send method to take over in case a service
     * does not specify one
     *
     * maybe sendData ??
     */

}
