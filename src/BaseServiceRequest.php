<?php

namespace Dmgctrlr\LaraOsrm;

use Dmgctrlr\LaraOsrm\Responses\RouteServiceGroupResponse;
use Illuminate\Support\Collection;

class BaseServiceRequest extends AbstractRequest
{
    protected $version = 'v1';
    protected $profile = 'driving';
    protected $format = 'json';

    public $service; // delegated to extending classes
    public $options; // delegated to extending classes
    public $coordinates; // all extending classes must set coordinates (some accept only one (nearest))
    public Collection $remainingCoordinates; // When using sendChunks() this is used to store the waypoints for the next chunk.

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

    /**
     * Sends the request in smaller chunks to overcome the OSRM maximum URL
     * length (since coordiantes are sent in GET not POST content).
     * Has a few limitations:
     * - only returns a single route (no alternatives)
     * - Doesn't return HTTP headers.
     * - If one request fails an error is thrown and the rest are chucked.
     * - Only merges the 'legs' section of the 'route', does no update waypoints, duration or geometery.
     */
    public function sendChunk($chunkSize = 100)
    {
        if ($this->options['geometries'] !== 'geojson') {
            throw new \Exception('sendChunk only works when geometries=geojson');
        }
        $groupResponse = new RouteServiceGroupResponse($this);

        $chunkSize = $this->adjustChunkSize(count($this->coordinates), $chunkSize);

        $this->remainingCoordinates = collect($this->coordinates);
        $this->coordinates = [];
        foreach ($this->remainingCoordinates->chunk($chunkSize)->all() as $theseCoordinates) {
            $this->setCoordinates($theseCoordinates->all());
            $response = $this->send();
            if ($response->isError()) {
                throw new \Exception($response->getMessage());
            }
            $groupResponse->addResponse($response);
        };

        return $groupResponse->stitch();
    }
    /**
     * Given the requested chunk size and number of waypoints, tweak the chunk size
     * if required because every chunk needs at least 2 waypoints.
     * */
    public function adjustChunkSize(int $waypointCount, int $chunkSize)
    {
        if ($chunkSize >= $waypointCount) {
            return $chunkSize;
        }
        // Every chunk needs at least 2 waypoints. A chunk with 0 waypoints won't
        // do anything so 0 is OK.
        if ($waypointCount % $chunkSize > 0 && $waypointCount % $chunkSize < 2) {
            $chunkSize++;
            // We're only guessing that adding 1 helps, so check (and add another 1 if necessary)
            return $this->adjustChunkSize($waypointCount, $chunkSize);
        }
        return $chunkSize;
    }

}
