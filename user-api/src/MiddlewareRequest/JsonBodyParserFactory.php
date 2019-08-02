<?php

namespace RcmUser\Api\MiddlewareRequest;

use Psr\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class JsonBodyParserFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return JsonBodyParser
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $serviceContainer)
    {
        return new JsonBodyParser();
    }
}
