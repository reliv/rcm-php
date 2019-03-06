<?php

namespace Rcm\HttpLib;

class ModuleConfig
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    JsonBodyParserMiddleware::class
                    => JsonBodyParserMiddlewareFactory::class
                ],
            ],
        ];
    }
}
