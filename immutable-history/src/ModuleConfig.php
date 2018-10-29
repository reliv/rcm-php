<?php

namespace Rcm\ImmutableHistory;

use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEventToSentence;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetAllChangeLogEventSentencesForDateRange;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogByDateRangeComposite;
use Rcm\ImmutableHistory\Controller\ChangeLogListController;
use Rcm\ImmutableHistory\Page\ImmutablePageVersion;
use Rcm\ImmutableHistory\Page\ImmutablePageVersionEntity;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Page\RcmPluginWrappersToRcmImmutablePluginInstances;
use RcmUser\Api\Acl\IsAllowed;

class ModuleConfig
{
    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    'Rcm\ImmutableHistory\PageVersionRepo' => [
                        'class' => VersionRepository::class,
                        'arguments' => [
                            ['literal' => ImmutablePageVersionEntity::class],
                            \Doctrine\ORM\EntityManager::class
                        ]
                    ],
                    GetAllChangeLogEventSentencesForDateRange::class => [
                        'arguments' => [
                            ChangeLogEventToSentence::class
                        ],
                        'calls' => [
                            ['addChild', [\Rcm\ImmutableHistory\Page\GetHumanReadibleChangeLogEventsByDateRange::class]]
                        ]
                    ],
                    ChangeLogEventToSentence::class => [],
                    ChangeLogListController::class => [
                        'arguments' => [
                            GetAllChangeLogEventSentencesForDateRange::class,
                            IsAllowed::class
                        ]
                    ],
                    \Rcm\ImmutableHistory\Page\GetHumanReadibleChangeLogEventsByDateRange::class => [
                        'arguments' => [
                            EntityManager::class
                        ]
                    ],
                    PageContentFactory::class => []
                ]
            ],
            'routes' => [
                [
                    'path' => '/rcm/change-log',
                    'allowed_methods' => ['GET'],
                    'middleware' => [
                        ChangeLogListController::class,
                    ],
                ],
            ],
            'doctrine' => [
                'driver' => [
                    'Rcm\ImmutableHistory\Page' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/Page'
                        ]
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'Rcm\ImmutableHistory\Page' => 'Rcm\ImmutableHistory\Page'
                        ]
                    ]
                ],
            ],
            'Acl\Config' => [
                'ResourceProviders' => [
                    'Pws\Acl\ResourceProvider\Pws' => [
                        'content-change-log' => [
                            'resourceId' => 'content-change-log',
                            'parentResourceId' => null,
                            'privileges' => [
                                'read',
                            ],
                            'name' => 'Content change log',
                            'description' => 'Contains a log of all known content changes.',
                        ],
                    ],
                ],
            ],
        ];
    }
}
