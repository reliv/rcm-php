<?php

namespace Rcm\ImmutableHistory;

use Doctrine\ORM\EntityManager;
use Rcm\ImmutableHistory\Acl\AclConstants;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogEventToSentence;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetAllSortedChangeLogEventsByDateRange;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogByDateRangeComposite;
use Rcm\ImmutableHistory\HumanReadableChangeLog\ChangeLogListController;
use Rcm\ImmutableHistory\HumanReadableChangeLog\GetHumanReadableChangeLogEventsByDateRange;
use Rcm\ImmutableHistory\Page\ImmutablePageVersion;
use Rcm\ImmutableHistory\Page\ImmutablePageVersionEntity;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Page\RcmPageNameToPathname;
use Rcm\ImmutableHistory\Page\RcmPluginWrappersToRcmImmutablePluginInstances;
use Rcm\ImmutableHistory\Redirect\ImmutableRedirectVersionEntity;
use Rcm\ImmutableHistory\I18nMessage\ImmutableI18nMessageVersionEntity;
use Rcm\ImmutableHistory\Site\HumanReadableDescriber;
use Rcm\ImmutableHistory\Site\ImmutableSiteVersionEntity;
use Rcm\ImmutableHistory\Site\SiteIdToDomainName;
use Rcm\ImmutableHistory\SiteSettingsSection\ImmutableSiteSettingsSectionVersionEntity;
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
                    'Rcm\ImmutableHistory\RedirectVersionRepo' => [
                        'class' => VersionRepository::class,
                        'arguments' => [
                            ['literal' => ImmutableRedirectVersionEntity::class],
                            \Doctrine\ORM\EntityManager::class,
                            GenerateResourceIdInterface::class
                        ]
                    ],
                    'Rcm\ImmutableHistory\SiteSettingsSectionVersionRepo' => [
                        'class' => VersionRepository::class,
                        'arguments' => [
                            ['literal' => ImmutableSiteSettingsSectionVersionEntity::class],
                            \Doctrine\ORM\EntityManager::class,
                            GenerateResourceIdInterface::class
                        ]
                    ],
                    'Rcm\ImmutableHistory\I18nMessageRepo' => [
                        'class' => VersionRepository::class,
                        'arguments' => [
                            ['literal' => ImmutableI18nMessageVersionEntity::class],
                            \Doctrine\ORM\EntityManager::class,
                            GenerateResourceIdInterface::class
                        ]
                    ],
                    GenerateResourceIdInterface::class => [
                        'class' => GenerateUuidV4::class
                    ],
                    GetAllSortedChangeLogEventsByDateRange::class => [
                        'calls' => [
                            [
                                'addChild',
                                [GetHumanReadableChangeLogEventsByDateRange::class]
                            ],
                        ]
                    ],
                    ChangeLogListController::class => [
                        'arguments' => [
                            GetAllSortedChangeLogEventsByDateRange::class,
                            IsAllowed::class
                        ]
                    ],
                    GetHumanReadableChangeLogEventsByDateRange::class => [
                        'arguments' => [
                            EntityManager::class,
                            UserIdToUserFullName::class
                        ],
                        'calls' => [
                            [
                                'addVersionType',
                                [
                                    ['literal' => ImmutableSiteVersionEntity::class],
                                    \Rcm\ImmutableHistory\Site\HumanReadableDescriber::class
                                ]
                            ],
                            [
                                'addVersionType',
                                [
                                    ['literal' => ImmutablePageVersionEntity::class],
                                    \Rcm\ImmutableHistory\Page\HumanReadableDescriber::class
                                ]
                            ],
                            [
                                'addVersionType',
                                [
                                    ['literal' => ImmutableSiteWideContainerVersionEntity::class],
                                    \Rcm\ImmutableHistory\SiteWideContainer\HumanReadableDescriber::class
                                ]
                            ],
                            [
                                'addVersionType',
                                [
                                    ['literal' => ImmutableRedirectVersionEntity::class],
                                    \Rcm\ImmutableHistory\Redirect\HumanReadableDescriber::class
                                ]
                            ],
                            [
                                'addVersionType',
                                [
                                    ['literal' => ImmutableSiteSettingsSectionVersionEntity::class],
                                    \Rcm\ImmutableHistory\SiteSettingsSection\HumanReadableDescriber::class
                                ]
                            ],
//                            [ //@TODO re-enable (temporarily disabled because was breaking local environment)
//                                'addVersionType',
//                                [
//                                    ['literal' => ImmutableI18nMessageVersionEntity::class],
//                                    \Rcm\ImmutableHistory\I18nMessage\HumanReadableDescriber::class
//                                ]
//                            ],
                        ]
                    ],
                    \Rcm\ImmutableHistory\Site\HumanReadableDescriber::class => [],
                    \Rcm\ImmutableHistory\Page\HumanReadableDescriber::class => [
                        'arguments' => [SiteIdToDomainName::class]
                    ],
                    \Rcm\ImmutableHistory\SiteWideContainer\HumanReadableDescriber::class => [
                        'arguments' => [SiteIdToDomainName::class]
                    ],
                    \Rcm\ImmutableHistory\Redirect\HumanReadableDescriber::class => [
                        'arguments' => [SiteIdToDomainName::class]
                    ],
                    \Rcm\ImmutableHistory\SiteSettingsSection\HumanReadableDescriber::class => [
                        'arguments' => [SiteIdToDomainName::class]
                    ],
                    \Rcm\ImmutableHistory\I18nMessage\HumanReadableDescriber::class => [],
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
                    'Rcm\ImmutableHistory\Redirect' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/Redirect'
                        ]
                    ],
                    'Rcm\ImmutableHistory\SiteSettingsSection' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/SiteSettingsSection'
                        ]
                    ],
                    'Rcm\ImmutableHistory\I18nMessage' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/I18nMessage'
                        ]
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'Rcm\ImmutableHistory\Site' => 'Rcm\ImmutableHistory\Site',
                            'Rcm\ImmutableHistory\Page' => 'Rcm\ImmutableHistory\Page',
                            'Rcm\ImmutableHistory\SiteWideContainer' => 'Rcm\ImmutableHistory\SiteWideContainer',
                            'Rcm\ImmutableHistory\Redirect' => 'Rcm\ImmutableHistory\Redirect',
                            'Rcm\ImmutableHistory\SiteSettingsSection' => 'Rcm\ImmutableHistory\SiteSettingsSection',
                            'Rcm\ImmutableHistory\I18nMessage' => 'Rcm\ImmutableHistory\I18nMessage'
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
