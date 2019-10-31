<?php

namespace Dmgctrlr\LaraOsrm;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

abstract class AbstractResponse
{
    private $response;
    private $responseJson;
    /**
     * @var Client
     */
    private $client;

    /**
     * AbstractRequest constructor.
     */
    public function __construct(GuzzleResponse $response)
    {
        $this->response = $response;
        $this->responseJson = \GuzzleHttp\json_decode($response->getBody());
    }

    public function getStatus()
    {
        return $this->responseJson->code;
    }

    public function getWaypoints()
    {
        return $this->responseJson->waypoints;
    }

    public function getRoute()
    {
        return $this->responseJson->routes;
    }

    public function getFirstRoute()
    {
        return $this->responseJson->routes[0];
    }
}