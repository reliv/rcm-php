<?php

namespace Rcm\RequestContext;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class GetRequestContext
{
    public static function invoke(ServerRequestInterface $request): ContainerInterface
    {
        return $request->getAttribute(RequestContextBindings::REQUEST_ATTRIBUTE);
    }

    public function __invoke(ServerRequestInterface $request): ContainerInterface
    {
        return self::invoke($request);
    }
}
