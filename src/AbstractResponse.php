<?php

namespace Dmgctrlr\LaraOsrm;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

abstract class AbstractResponse
{
    protected $response;
    protected $responseJson;
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
        $this->responseJson = \GuzzleHttp\json_decode((string) $response->getBody());
    }

    public function dumpResponse($echo = true)
    {
        if ($echo) {
            var_export($this->responseJson);
        } else {
            var_export($this->responseJson, true);
        }
    }
}
