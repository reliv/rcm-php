<?php

namespace Rcm\Acl;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\Controller\TestController;
use Rcm\Acl\Service\GetAllGroups;
use Rcm\Acl\Service\GetCurrentUserId;
use Rcm\Acl\Service\GetCurrentUserIdFactory;
use Rcm\Acl\Service\GetGroupIdsByUserId;
use Rcm\Acl\Service\GroupsAndQueryToApplicableRules;
use Rcm\Acl\Service\GroupsAndQueryToQueryResult;
use Rcm\Acl\Service\RulesAndQueryToResult;

class ModuleConfig
{
    public function __invoke()
    {
        return [
            'request_context' => [
                'factories' => [
                    IsAllowed::class => IsAllowedFactory::class,
                    GetCurrentUserId::class => GetCurrentUserIdFactory::class
                ],
            ],
            'dependencies' => [
                'config_factories' => [
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
//                            GetGroupIdsByUserId::class
//                        ]
//                    ],
                    IsAllowedByUserId::class => [
                        'arguments' => [
                            RunQuery::class,
                            GetGroupIdsByUserId::class
                        ]
                    ],
                    RunQuery::class => [
                        'arguments' => [
                            GetAllGroups::class,
                            GroupsAndQueryToQueryResult::class
                        ]
                    ],
                    GetGroupIdsByUserId::class => [
                        'arguments' => [
                            EntityManager::class,
                            GroupsAndQueryToQueryResult::class
                        ]
                    ],
                    GetAllGroups::class => [
                        'arguments' => [
                            EntityManager::class,
                        ]
                    ],
                    TestController::class => [
//                        'arguments' => [
//                            IsAllowed::class
//                        ]
                    ],
//                    GetCurrentUserId::class => [
//                        'arguments' => [
//                            CurrentRequest::class,
//                            GetCurrentUser::class
//                        ]
//                    ]
                ]
            ],
            'routes' => [
                '/temp-test-rcm-acl' => [
                    // @TODO Delete this test controller eventually (it is safe to keep for now though)
                    'path' => '/temp-test-rcm-acl',
                    'middleware' => TestController::class,
                    'allowed_methods' => ['GET'],
                ],
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
