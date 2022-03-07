<?php

namespace Itwmw\Docker;

use Closure;
use GuzzleHttp\Client;

class Images extends BaseDocker
{
    public function __construct(protected Client $client)
    {
    }

    /**
     * Inspect an image
     *
     * Return low-level information about an image.
     *
     * @param string $name Image name or id
     * @return array
     * @throws DockerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function inspect(string $name): array
    {
        $name     = str_replace(':', '%3A', $name);
        $response = $this->client->get("/images/{$name}/json");
        $this->handlerStatusCode($response);
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Determining whether an images exist
     *
     * @param string $name Image name or id
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function has(string $name): bool
    {
        try {
            $this->inspect($name);
            return true;
        } catch (DockerException) {
            return false;
        }
    }

    /**
     * Pull the Images
     *
     * @param string $image
     * @param bool|callable|Closure $showProgress Whether to show progress
     * @throws DockerException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @noinspection PhpFullyQualifiedNameUsageInspection
     */
    public function pull(string $image, bool|callable|Closure $showProgress = false)
    {
        $response = $this->client->post('/images/create', [
            'query' => [
                'fromImage' => $image
            ],
            'stream' => !(false === $showProgress)
        ]);

        $this->handlerStatusCode($response);

        if (false !== $showProgress) {
            $body = $response->getBody();
            while (!$body->eof()) {
                if (is_callable($showProgress)) {
                    call_user_func($showProgress, $body->read(1024));
                } else {
                    echo $body->read(1024);
                }
            }
        }
    }
}
