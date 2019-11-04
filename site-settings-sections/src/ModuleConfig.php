<?php

namespace Rcm\SiteSettingsSections;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\IsAllowedByUser;
use Rcm\HttpLib\JsonBodyParserMiddleware;
use Rcm\Service\CurrentSite;
use RcmUser\Api\Authentication\GetIdentity;

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
                    SetSection::class => [
                        'arguments' => [
                            GetSectionDefinitions::class,
                            EntityManager::class,
                            'Rcm\ImmutableHistory\SiteSettingsSectionVersionRepo'
                        ],
                    ],
                    HttpGetSiteSettingsSectionController::class => [
                        'arguments' => [
                            IsAllowedByUser::class,
                            GetSection::class,
                            EntityManager::class,
                            CurrentSite::class,
                            GetIdentity::class
                        ],
                    ],
                    HttpPutSiteSettingsSectionController::class => [
                        'arguments' => [
                            IsAllowedByUser::class,
                            SetSection::class,
                            GetSection::class,
                            EntityManager::class,
                            CurrentSite::class,
                            GetIdentity::class
                        ],
                    ],
                ],
            ],
            'routes' => [
                [
                    'allowed_methods' => ['GET'],
                    'path' => '/api/rcm/site-settings-section/current/{sectionName}',
                    'middleware' => [HttpGetSiteSettingsSectionController::class],
                ],
                [
                    'allowed_methods' => ['PUT'],
                    'path' => '/api/rcm/site-settings-section/current/{sectionName}',
                    'middleware' => [
                        JsonBodyParserMiddleware::class,
                        HttpPutSiteSettingsSectionController::class
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
//                    'example1' => [
//                        'label' => 'Example 1',
//                        'fields' => [
//
//                        ],
//                    ],
//                    'example2' => [
//                        'label' => 'Example 2',
//                        'fields' => [
//
//                        ],
//                    ],
                ]
            ],
        ];
    }
}
