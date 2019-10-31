<?php

namespace Dmgctrlr\LaraOsrm;

use GuzzleHttp\Client;

use Dmgctrlr\LaraOsrm\Responses\RouteServiceResponse;

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
    public function __construct($config = [])
    {
        $host = isset($config['host']) ? $config['host'] : config('lara-osrm.host', 'router.project-osrm.org');
        $port = isset($config['port']) ? $config['port'] : config('lara-osrm.port', '80');
        $this->url = $host . ':' . $port . '/';
        $this->client = new Client();
    }

    public function send()
    {
        $this->url .= $this->buildBaseURL() . '/' . $this->buildCoordinatesURL() . '?' . $this->buildOptionsURL();
        $curlResponse = $this->client->get($this->url, ['connect_timeout' => 3.14]);
        switch ($this->service) {
            case 'route':
                return new RouteServiceResponse($curlResponse);
        }
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
