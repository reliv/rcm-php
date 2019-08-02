<?php

namespace Rcm\CurrentRequest;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class CurrentRequestFactory
{
    public function __invoke(ContainerInterface $container): ServerRequestInterface
    {
        return $container->get(GetCurrentRequest::class)->__invoke();
    }
}
