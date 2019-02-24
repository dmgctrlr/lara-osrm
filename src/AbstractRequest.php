<?php

namespace Dmgctrlr\LaraOsrm;

use GuzzleHttp\Client;

abstract class AbstractRequest
{
    private $url;
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
        $this->client = new Client();
    }

    public function send()
    {
        $this->url .= $this->buildBaseURL() . '/' . $this->buildCoordinatesURL() . '?' . $this->buildOptionsURL();

        return $this->client->post($this->url, ['connect_timeout' => 3.14]);
    }

    /**
     * Add the service, version and profile to the URL
     * @return string
     */
    private function buildBaseURL(): string
    {
        return $this->service . '/' . $this->version . '/' . $this->profile;
    }

    /**
     * Create the coordinates url string
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
     * Create the options url query
     * @return string
     */
    public function buildOptionsURL(): string
    {
        // set to query_string the array of options
        return http_build_query($this->options); // todo test null/false etc
    }

}
