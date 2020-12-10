<?php

namespace Dmgctrlr\LaraOsrm;

use Dmgctrlr\LaraOsrm\Responses\RouteServiceResponse;
use Dmgctrlr\LaraOsrm\AbstractResponse;
use Dmgctrlr\Laraosrm\Models\LatLng;

abstract class AbstractGroupResponse
{
    private BaseServiceRequest $baseRequest;
    private $responses = [];
    private $responseData;

    /**
     * AbstractGroupResponse constructor.
     * 
     * @param BaseServiceRequest $baseRequest A request which was used to make some responses - used to make link responses
     * @param array $responses An array to responses to group.
     */
    public function __construct(BaseServiceRequest $baseRequest, $responses = [])
    {
        $this->baseRequest = $baseRequest;
        $this->responses = collect($responses);
    }

    public function addResponse(RouteServiceResponse $response): self
    {
        $this->responses[] = $response;
        echo "Added response #" . count($this->responses) . "\n";
        return $this;
    }

    /**
     * Having added all our responses, stitch them together by calculating
     * the routes between and merging them into a single response to return.
     */
    public function stitch(): RouteServiceResponse
    {
        // Skip the first one since it doesn't need a link before it.
        $linkedResponses = collect([
            $this->responses[0]
        ]);
        for ($i = 1; $i < count($this->responses); $i++) {
            $previousResponse = $linkedResponses->last();
            if (!is_a($previousResponse, AbstractResponse::class)) {
                throw new \Exception('$previousResponse on iteration ' . $i . ' is not a Response object but is a ' . get_class($previousResponse));
            }
            $thisResponse = $this->responses[$i];
            if (!is_a($thisResponse, AbstractResponse::class)) {
                throw new \Exception('Encountered response #' . $i . ' which is not a Response object but is a ' . get_class($thisResponse));
            }

            /**
             * Make a Link response which routes from the last coordinate
             * of the previous response, to the first of the next.
             */
            $linkRequest = clone $this->baseRequest;

            $lastWaypoint = collect($previousResponse->getWaypoints())->last();
            $nextWaypoint = $thisResponse->getWaypoints()[0];

            $linkRequest->setCoordinates([
                new LatLng($lastWaypoint->location[1], $lastWaypoint->location[0]),
                new LatLng($nextWaypoint->location[1], $nextWaypoint->location[0]),
            ]);

            $linkedResponses[] = $linkRequest->send();
            $linkedResponses[] = $thisResponse;
        }
        return $this->mergeResponses($linkedResponses);
    }

    private function mergeResponses($responses)
    {

        $this->responseData = null;
        foreach ($responses as $response) {
            $responseData = $response->getResponseData();
            if (empty($this->responseData)) {
                // The first response we just take the whole body. We then add to this one later
                $this->responseData = $responseData;
            } else {
                $this->mergeRoutes($responseData->routes);
                $this->mergeWaypoints($responseData->waypoints);
            }
        }

        $responseName = get_class($this->responses[0]);
        $response = new $responseName($this->responseData);
        return $response;
    }

    private function mergeWaypoints($waypoints)
    {
        echo "Waypoints: " . count($this->responseData->waypoints) . "\n";

        // Merge the Waypoints if we have some.
        foreach ($waypoints as $waypoint) {
            $this->responseData->waypoints[] = $waypoint;
        }
        return $this;
    }

    private function mergeRoutes($routes)
    {
        foreach ($routes as $routeId => $route) {
            if (!isset($this->responseData->routes[$routeId])) {
                $this->responseData->routes[$routeId] = $route;
            } else {
                // Merge the coordinates
                if (isset($route->geometry) && isset($route->geometry->coordinates)) {
                    array_shift($route->geometry->coordinates);
                }

                foreach ($route->geometry->coordinates as $coordinate) {
                    // $coordinate[] = 'chunk_id_' . $chunkId;
                    array_push($this->responseData->routes[$routeId]->geometry->coordinates, $coordinate);
                }
            }

            // Merge the Legs
            if (isset($route->legs)) {
                foreach ($route->legs as $leg) {
                    array_push($this->responseData->routes[$routeId]->legs, $leg);
                }
            }

            // Merge the totals
            $this->responseData->routes[$routeId]->weight += $route->weight;
            $this->responseData->routes[$routeId]->distance += $route->distance;
            $this->responseData->routes[$routeId]->duration += $route->duration;
        }
        return $this;
    }
}
