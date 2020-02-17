<?php

namespace Rcm\SiteSettingsSections;

use Doctrine\ORM\EntityManager;

class ModuleConfig
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    GetSectionDefinitions::class => [
                        'arguments' => [
                            ['from_config' => [__NAMESPACE__, 'sections']],
                        ]
                    ],
                    GetSection::class => [
                        'arguments' => [
                            GetSectionDefinitions::class,
                            EntityManager::class
                        ],
                    ],
                ],
            ],
            'doctrine' => [
                'driver' => [
                    __NAMESPACE__ => [
                        'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                        'cache' => 'array',
                        'paths' => [
                            __DIR__,
                        ]
                    ],
                    'orm_default' => [
                        'drivers' => [
                            __NAMESPACE__ => __NAMESPACE__
                        ]
                    ]
                ]
            ],
            __NAMESPACE__ => [
                'sections' => [
                    // // NOTE: These examples are still technically accurate
                    // //       in terms of data structure, but the current
                    // //       convention is to load the config from Yaml files
                    // //       using the Symfony Yaml parser, not put them in
                    // //       the module config directly.
                    // 'example1' => [
                    //     'label' => 'Example 1',
                    //     'fields' => [
                    // 
                    //     ],
                    // ],
                    // 'example2' => [
                    //     'label' => 'Example 2',
                    //     'fields' => [
                    // 
                    //     ],
                    // ],
                ]
            ],
        ];
    }
}
