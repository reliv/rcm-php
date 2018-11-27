<?php

namespace Rcm\ImmutableHistory;

use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\Acl\AclConstants;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEventToSentence;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetAllSortedChangeLogEventsByDateRange;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogByDateRangeComposite;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogListController;
use Rcm\ImmutableHistory\Page\ImmutablePageVersion;
use Rcm\ImmutableHistory\Page\ImmutablePageVersionEntity;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Page\RcmPageNameToPathname;
use Rcm\ImmutableHistory\Page\RcmPluginWrappersToRcmImmutablePluginInstances;
use Rcm\ImmutableHistory\Site\ImmutableSiteVersionEntity;
use Rcm\ImmutableHistory\Site\SiteIdToDomainName;
use Rcm\ImmutableHistory\SiteWideContainer\ImmutableSiteWideContainerVersionEntity;
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
                    'Rcm\ImmutableHistory\SiteVersionRepo' => [
                        'class' => VersionRepository::class,
                        'arguments' => [
                            ['literal' => ImmutableSiteVersionEntity::class],
                            \Doctrine\ORM\EntityManager::class,
                            GenerateResourceIdInterface::class
                        ]
                    ],
                    'Rcm\ImmutableHistory\PageVersionRepo' => [
                        'class' => VersionRepository::class,
                        'arguments' => [
                            ['literal' => ImmutablePageVersionEntity::class],
                            \Doctrine\ORM\EntityManager::class,
                            GenerateResourceIdInterface::class
                        ]
                    ],
                    'Rcm\ImmutableHistory\SiteWideContainerVersionRepo' => [
                        'class' => VersionRepository::class,
                        'arguments' => [
                            ['literal' => ImmutableSiteWideContainerVersionEntity::class],
                            \Doctrine\ORM\EntityManager::class,
                            GenerateResourceIdInterface::class
                        ]
                    ],
                    GenerateResourceIdInterface::class => [
                        'class' => GenerateUuidV4::class
                    ],
                    GetAllSortedChangeLogEventsByDateRange::class => [
                        'calls' => [
                            ['addChild', [\Rcm\ImmutableHistory\Page\GetHumanReadibleChangeLogEventsByDateRange::class]]
                        ]
                    ],
                    ChangeLogListController::class => [
                        'arguments' => [
                            GetAllSortedChangeLogEventsByDateRange::class,
                            IsAllowed::class
                        ]
                    ],
                    \Rcm\ImmutableHistory\Page\GetHumanReadibleChangeLogEventsByDateRange::class => [
                        'arguments' => [
                            EntityManager::class,
                            SiteIdToDomainName::class,
                            UserIdToUserFullName::class
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
                    'Rcm\ImmutableHistory\Site' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/Site'
                        ]
                    ],
                    'Rcm\ImmutableHistory\Page' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/Page'
                        ]
                    ],
                    'Rcm\ImmutableHistory\SiteWideContainer' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/SiteWideContainer'
                        ]
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'Rcm\ImmutableHistory\Site' => 'Rcm\ImmutableHistory\Site',
                            'Rcm\ImmutableHistory\Page' => 'Rcm\ImmutableHistory\Page',
                            'Rcm\ImmutableHistory\SiteWideContainer' => 'Rcm\ImmutableHistory\SiteWideContainer'
                        ]
                    ]
                ],
            ],
            'RcmUser' => [
                'Acl\Config' => [
                    'ResourceProviders' => [
                        'Rcm\ImmutableHistory\ResourceProvider' => [
                            AclConstants::CONTENT_CHANGE_LOG => [
                                'resourceId' => AclConstants::CONTENT_CHANGE_LOG,
                                'parentResourceId' => null,
                                'privileges' => [
                                    AclConstants::READ,
                                ],
                                'name' => 'ContentChangeLog',
                                'description' => 'Contains a log of all known content changes.',
                            ],
                        ],
                    ],
                ],
            ]
        ];
    }
}
