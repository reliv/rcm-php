<?php

namespace Rcm\CurrentRequest;

class ModuleConfig
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'factories' => [
                    CurrentRequest::class => CurrentRequestFactory::class
                ],
                'config_factories' => [
                    GetCurrentRequest::class => [],
                    CurrentRequestEarlyMiddleware::class => [
                        'arguments' => [
                            GetCurrentRequest::class
                        ]
                    ]
                ]
            ]
        ];
    }
}
