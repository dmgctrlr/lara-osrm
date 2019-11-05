<?php

namespace Dmgctrlr\LaraOsrm;

use GuzzleHttp\Client;

use Dmgctrlr\LaraOsrm\Responses\RouteServiceResponse;

abstract class AbstractRequest
{
    private $host;
    private $port;
    /**
     * @var Client
     */
    private $client;

    /**
     * AbstractRequest constructor.
     */
    public function __construct($config = [])
    {
        $this->host = isset($config['host']) ? $config['host'] : config('lara-osrm.host', 'router.project-osrm.org');
        $this->port = isset($config['port']) ? $config['port'] : config('lara-osrm.port', '80');
        $this->client = new Client();
    }

    public function send()
    {
        $url = $this->host
            . ':'
            . $this->port
            . '/'
            . $this->buildBaseURL()
            . '/'
            . $this->buildCoordinatesURL()
            . '?'
            . $this->buildOptionsURL();

        $curlResponse = $this->client->get($url, ['connect_timeout' => 3.14]);
        if ($curlResponse->getStatusCode() !== 200) {
            throw new \Exception('API response was not 200');
        }
        switch ($this->service) {
            case 'route':
                $response = new RouteServiceResponse($curlResponse);
                break;
            default:
                throw new \Exception('I cannot handle this service type yet: ' . $this->service);
        }
        if ($response->isError()) {
            throw new \Exception($response->getMessage());
        }
        return $response;
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
        if (!isset($this->coordinates)) {
            throw new \Exception('Coordinates not set');
        }
        $coordinates = [];
        foreach ($this->coordinates as $coordinate) {
            // Note the API wants Longitude then Latitude
            $coordinates[] = $coordinate->getLng() . ',' . $coordinate->getLat();
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
