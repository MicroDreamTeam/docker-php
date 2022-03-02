<?php

namespace Itwmw\Docker;

use GuzzleHttp\Client;

class Docker
{
    protected Client $client;

    protected Containers $containers;

    protected Images $images;

    public function __construct(string $dockerApiUrl = 'http://127.0.0.1:2375/')
    {
        $this->client = new Client([
            'base_uri'    => $dockerApiUrl,
            'http_errors' => false,
            'timeout'     => 0,
        ]);
    }

    public function containers(): Containers
    {
        if (!isset($this->containers)) {
            $this->containers = new Containers($this->client);
        }
        return $this->containers;
    }

    public function images(): Images
    {
        if (!isset($this->images)) {
            $this->images = new Images($this->client);
        }
        return $this->images;
    }
}
