<?php

namespace Rcm\HttpLib;

use Psr\Container\ContainerInterface;
use Reliv\RcmApiLib\Api\ApiResponse\NewPsrResponseWithTranslatedMessages;

class JsonBodyParserMiddlewareFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return JsonBodyParserMiddleware
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        return new JsonBodyParserMiddleware(
            $container->get(NewPsrResponseWithTranslatedMessages::class)
        );
    }
}
