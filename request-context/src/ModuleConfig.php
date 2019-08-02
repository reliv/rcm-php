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
            ],
            // This makes ZfConfigFactories work with the requst_context container/service-manager
            RequestContextBindings::REQUEST_CONTEXT_CONTAINER_CONFIG_KEY => [
                'abstract_factories' => [
                    \Rcm\RequestContext\ZfConfigFactoriesRequestContextFactory::class
                ],
            ]
        ];
    }
}
