<?php

namespace Rcm\RequestContext;

class ModuleConfig
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    InitRequestContextMiddleware::class => [
                        'arguments' => [
                            'config',
                            'service_manager'
                        ]
                    ],
                ]
            ]
        ];
    }
}
