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
use Rcm\ImmutableHistory\Page\RcmPageNameToPathname;
use Rcm\ImmutableHistory\Page\RcmPluginWrappersToRcmImmutablePluginInstances;
use Rcm\ImmutableHistory\Site\SiteIdToDomainName;
use Rcm\ImmutableHistory\User\UserIdToUserFullName;
use Rcm\ImmutableHistory\ResourceId\GenerateResourceIdInterface;
use Rcm\ImmutableHistory\ResourceId\GenerateUuidV4;
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
                            \Doctrine\ORM\EntityManager::class,
                            GenerateResourceIdInterface::class
                        ]
                    ],
                    GenerateResourceIdInterface::class => [
                        'class' => GenerateUuidV4::class
                    ],
                    GetAllChangeLogEventSentencesForDateRange::class => [
                        'arguments' => [
                            ChangeLogEventToSentence::class
                        ],
                        'calls' => [
                            ['addChild', [\Rcm\ImmutableHistory\Page\GetHumanReadibleChangeLogEventsByDateRange::class]]
                        ]
                    ],
                    ChangeLogEventToSentence::class => [
                        'arguments' => [
                            UserIdToUserFullName::class
                        ]
                    ],
                    ChangeLogListController::class => [
                        'arguments' => [
                            GetAllChangeLogEventSentencesForDateRange::class,
                            IsAllowed::class
                        ]
                    ],
                    \Rcm\ImmutableHistory\Page\GetHumanReadibleChangeLogEventsByDateRange::class => [
                        'arguments' => [
                            EntityManager::class,
                            SiteIdToDomainName::class
                        ]
                    ],
                    PageContentFactory::class => [],
                    UserIdToUserFullName::class => [],
                    SiteIdToDomainName::class => [
                        'arguments' => [
                            EntityManager::class
                        ]
                    ],
                    RcmPageNameToPathname::class => []
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
