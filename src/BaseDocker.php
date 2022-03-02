<?php

namespace Itwmw\Docker;

use Psr\Http\Message\ResponseInterface;

class BaseDocker
{
    protected function handlerStatusCode(ResponseInterface $response, array $success = [200])
    {
        if (!in_array($response->getStatusCode(),$success)){
            throw new DockerException($response->getBody()->getContents(), $response->getStatusCode());
        }
    }
}