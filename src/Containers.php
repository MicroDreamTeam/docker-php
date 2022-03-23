<?php

namespace Itwmw\Docker;

use GuzzleHttp\Client;
use Itwmw\Docker\Structure\ImageName;

class Containers extends BaseDocker
{
    public function __construct(protected Client $client)
    {
    }

    /**
     * Create a container
     *
     * @param string $name Assign the specified name to the container. Must match `/?[a-zA-Z0-9][a-zA-Z0-9_.-]+`.
     * @param array $options
     *
     * @return void
     * @throws DockerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function create(string $name, array $options = [])
    {
        $response = $this->client->post('/containers/create', [
            'query' => [
                'name' => $name
            ],
            'json' => $options
        ]);

        $this->handlerStatusCode($response, [201]);
    }

    /**
     * Inspect a container
     *
     * Return low-level information about a container.
     *
     * @param string $id   ID or name of the container
     * @param bool   $size Return the size of container as fields `SizeRw` and `SizeRootFs`
     * @return array
     * @throws DockerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function inspect(string $id, bool $size = false): array
    {
        $response = $this->client->get("/containers/{$id}/json", [
            'query' => [
                'size' => $size
            ]
        ]);
        $this->handlerStatusCode($response);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Specify whether the container exists
     *
     * @param string $id ID or name of the container
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function has(string $id): bool
    {
        try {
            $this->inspect($id);
            return true;
        } catch (DockerException) {
            return false;
        }
    }

    /**
     * Remove a container
     *
     * @param string $id    ID or name of the containe
     * @param bool   $v     Remove anonymous volumes associated with the container.
     * @param bool   $force If the container is running, kill it before removing it.
     * @param bool   $link  Remove the specified link associated with the container.
     * @return void
     * @throws DockerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function remove(string $id, bool $v = false, bool $force = false, bool $link = false)
    {
        $response = $this->client->delete("/containers/{$id}", [
            'query' => [
                'force' => $force,
                'v'     => $v,
                'link'  => $link,
            ]
        ]);

        $this->handlerStatusCode($response, [204]);
    }

    /**
     * Stop a container
     *
     * @param string $id       ID or name of the container
     * @param int    $waitTime Number of seconds to wait before killing the container
     * @return void
     * @throws DockerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function stop(string $id, int $waitTime = 0)
    {
        $queryParams = [];
        if ($waitTime > 0) {
            $queryParams['t'] = $waitTime;
        }

        $response = $this->client->post("/containers/{$id}/stop", [
            'query' => $queryParams
        ]);

        $this->handlerStatusCode($response, [204]);
    }

    /**
     * Start a container
     *
     * @param string $id         ID or name of the container
     * @param string $detachKeys Override the key sequence for detaching a container. Format is a
                                 single character `[a-Z]` or `ctrl-<value>` where `<value>` is one
                                 of: `a-z`, `@`, `^`, `[`, `,` or `_`.
     * @return void
     * @throws DockerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function start(string $id, string $detachKeys = '')
    {
        $queryParams = [];
        if (!empty($detachKeys)) {
            $queryParams['detachKeys'] = $detachKeys;
        }

        $response = $this->client->post("/containers/{$id}/start", [
            'query' => $queryParams
        ]);

        $this->handlerStatusCode($response, [204, 304]);
    }

    /**
     * Restart a container
     *
     * @param string $id       ID or name of the container
     * @param int    $waitTime Number of seconds to wait before killing the container
     * @return void
     * @throws DockerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function restart(string $id, int $waitTime = 0)
    {
        $queryParams = [];
        if ($waitTime > 0) {
            $queryParams['t'] = $waitTime;
        }

        $response = $this->client->post("/containers/{$id}/restart", [
            'query' => $queryParams
        ]);

        $this->handlerStatusCode($response, [204]);
    }

    /**
     * Get container state
     *
     * ContainerState stores container's running state. It's part of ContainerJSONBase and will be returned by the "inspect" command.
     *
     * @param string $id ID or name of the container
     * @return State
     * @throws DockerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function state(string $id): State
    {
        return new State($this->inspect($id)['State']);
    }

    /**
     * Get the name of the image to use when creating the container/
     *
     * @param string $id ID or name of the container
     * @return ImageName
     * @throws DockerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function image(string $id): ImageName
    {
        return new ImageName($this->inspect($id)['Config']['Image'] ?? '');
    }
}
