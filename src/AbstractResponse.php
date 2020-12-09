<?php

namespace Dmgctrlr\LaraOsrm;

use GuzzleHttp\Psr7\Response as GuzzleResponse;

abstract class AbstractResponse
{
    const STATUS_OK = 'Ok'; // Request could be processed as expected.
    const STATUS_INVALID_URL = 'InvalidUrl'; // URL string is invalid.
    const STATUS_INVALID_SERVICE = 'InvalidService'; // Service name is invalid.
    const STATUS_INVALID_VERSION = 'InvalidVersion'; // Version is not found.
    const STATUS_INVALID_OPTIONS = 'InvalidOptions'; // Options are invalid.
    const STATUS_INVALID_QUERY = 'InvalidQuery'; // The query string is synctactically malformed.
    const STATUS_INVALID_VALUE = 'InvalidValue'; // The successfully parsed query parameters are invalid.
    const STATUS_NO_SEGMENT = 'NoSegment'; // One of the supplied input coordinates could not snap to street segment.
    const STATUS_TOO_BIG = 'TooBig'; // The request size violates one of the service specific request size restrictions.

    protected $response; // Holds the raw GuzzleResposne object
    protected $responseData; // StdObject holding the data from $response
    /**
     * @var Client
     */
    private $client;

    /**
     * AbstractRequest constructor.
     * 
     * @param $response GuzzleResponse|array
     */
    public function __construct($response)
    {
        if (is_a($response, GuzzleResponse::class)) {
            $responseData = \GuzzleHttp\json_decode((string) $response->getBody());
        } else {
            $responseData = $response;
        }
        $this->response = $response;
        $this->responseData = $responseData;
    }
    public function getStatus()
    {
        return $this->responseData->code;
    }

    public function isError()
    {
        return !$this->isOk();
    }

    public function isOk()
    {
        return $this->responseData->code === self::STATUS_OK;
    }

    public function getMessage()
    {
        return $this->responseData->message;
    }

    public function dumpResponse($echo = true)
    {
        if ($echo) {
            var_export($this->responseData);
        } else {
            var_export($this->responseData, true);
        }
    }

    public function getResponseData()
    {
        return $this->responseData;
    }
}
