<?php

namespace Rcm\Acl;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\Controller\TestController;
use Rcm\Acl\Service\GetAllGroups;
use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\GetGroupNamesByUser;
use Rcm\Acl\Service\GroupsAndQueryToApplicableRules;
use Rcm\Acl\Service\GroupsAndQueryToQueryResult;
use Rcm\Acl\Service\RulesAndQueryToResult;
use Rcm\RequestContext\CurrentRequest;
use Rcm\RequestContext\RequestContextBindings;
use \RcmUser\Api\Authentication\GetCurrentUser as GetUserByRequest;

class ModuleConfig
{
    public function __invoke()
    {
        return [
            'request_context' => [
                'config_factories' => [
                    AssertIsAllowed::class => [
                        'arguments' => [
                            IsAllowed::class
                        ]
                    ],
                    IsAllowed::class => [
                        'arguments' => [
                            GetCurrentUser::class,
                            IsAllowedByUser::class
                        ]
                    ],
                    GetCurrentUserId::class => [
                        'arguments' => [
                            CurrentRequest::class,
                            GetCurrentUser::class
                        ]
                    ],
                    GetCurrentUser::class => [
                        'arguments' => [
                            CurrentRequest::class,
                            GetUserByRequest::class
                        ]
                    ],
                ]
            ],
            'dependencies' => [
                'config_factories' => [
                    GetGroupNamesByUserInterface::class => [
                        'class' => GetGroupNamesByUser::class,
                        'arguments' => [
                            EntityManager::class
                        ]
                    ],
                    GroupsAndQueryToQueryResult::class => [
                        'arguments' => [
                            GroupsAndQueryToApplicableRules::class,
                            RulesAndQueryToResult::class
                        ]
                    ],
                    GroupsAndQueryToApplicableRules::class => [],
                    RulesAndQueryToResult::class => [],
//                    IsAllowed::class => [
//                        'arguments' => [
//                            RunQuery::class,
//                            GetCurrentUserId::class,
//                            GroupNamesByUser::class
//                        ]
//                    ],
                    IsAllowedByUser::class => [
                        'arguments' => [
                            RunQuery::class,
                            GetGroupNamesByUserInterface::class
                        ]
                    ],
                    RunQuery::class => [
                        'arguments' => [
                            GetAllGroups::class,
                            GroupsAndQueryToQueryResult::class
                        ]
                    ],
                    GroupNamesByUser::class => [
                        'arguments' => [
                            EntityManager::class,
                            GroupsAndQueryToQueryResult::class
                        ]
                    ],
                    GetAllGroups::class => [
                        'arguments' => [
                            ['literal' => __DIR__ . '/../../../../../acl/groups'],
                        ]
                    ],

                ]
            ],
            'doctrine' => [
                'driver' => [
                    'Rcm\Acl' => [
                        'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/Entity'
                        ]
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'Rcm\Acl' => 'Rcm\Acl'
                        ]
                    ]
                ]
            ],
        ];
    }
}
