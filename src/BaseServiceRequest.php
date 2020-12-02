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
     * @param string $name
     * @return $this
     */
    public function setProfile(?string $name = 'driving')
    {
        $this->profile = $name;
        return $this;
    }

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
     * Append a coordinate to the coordinates array
     * @param array $coordinates
     * @return BaseServiceRequest
     */
    public function addCoordinate(array $coordinates): self
    {
        $this->coordinates[] = $coordinates;
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
            $this->addOption($option);
        }

        return $this;
    }

    /**
     * Add an option
     * @param $option
     */
    public function addOption($option): void
    {
        if (is_array($option)) {
            if (array_key_exists(array_keys($option)[0], $this->options)) {
                $this->options[array_keys($option)[0]] = array_values($option)[0];
            }
        } else {
            if (array_key_exists($option, $this->options)) {
                $this->options[$option] = 'true';
            }
        }
    }

}
