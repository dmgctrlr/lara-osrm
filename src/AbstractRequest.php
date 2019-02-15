<?php

namespace Dmgctrlr\LaraOsrm;

use GuzzleHttp\Client;

abstract class AbstractRequest
{
    public $url;
    /**
     * @var Client
     */
    private $client;

    /**
     * AbstractRequest constructor.
     */
    public function __construct()
    {
        $this->url = config('lara-osrm.host') . ':' . config('lara-osrm.port') . '/';
        $this->client = new Client(); // doesn't seem right to do this here
    }

    public function send()
    {
        // build url here?
        $this->url .= $this->buildBaseURL().'/'. $this->buildCoordinatesURL().'?'.$this->buildOptionsURL();

        // maybe we should throw an exception here if the request fails


        return $this->client->post($this->url);
    }

    /**
     * Add the service, version and profile to the URL
     * @return string
     */
    private function buildBaseURL(): string
    {
        return $this->service . '/' . $this->version . '/' . $this->profile;
    }

    abstract function buildCoordinatesURL();

    abstract function buildOptionsURL();

}
