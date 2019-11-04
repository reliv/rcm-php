<?php

namespace Rcm\ContentConfig;

use Rcm\Api\Acl\IsPageAllowedForReading;

class ModuleConfig
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    CreatePage::class => [
                        'arguments' => []
                    ],
                ],
            ],
            'controllers' => [
                'config_factories' => [
                    ContentConfigController::class => [
                        'arguments' => [
                            IsPageAllowedForReading::class,
                            \Rcm\Page\Renderer\PageRendererBc::class,
                            CreatePage::class,
                            \Rcm\Service\CurrentSite::class,
                        ],
                    ]
                ],
            ],
            'view_manager' => [
                'template_path_stack' => [
                    __DIR__ . '/../view',
                ],
            ],
        ];
    }
}
