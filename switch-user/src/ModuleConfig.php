<?php

namespace Rcm\SwitchUser;

use Rcm\Acl\AssertIsAllowed;
use Rcm\Acl\IsAllowedByUser;
use Rcm\RequestContext\RequestContext;
use Rcm\SwitchUser\Acl\AssertCurrentUserIsAllowedToSwitchUser;
use Rcm\SwitchUser\Acl\DoesAclSayUserCanSU;
use Rcm\SwitchUser\Service\AssertImpersonatorIsAllowed;
use Rcm\SwitchUser\Service\AssertImpersonatorOrUserIsAllowed;
use Rcm\SwitchUser\Service\SwitchUserAclService;
use Rcm\SwitchUser\Service\SwitchUserService;
use RcmUser\Api\Acl\IsUserAllowed;
use RcmUser\Api\Authentication\Authenticate;
use RcmUser\Api\Authentication\GetIdentity;
use RcmUser\Api\Authentication\SetIdentityInsecure;
use RcmUser\Api\User\GetUserByUsername;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ModuleConfig
{
    public function __invoke()
    {
        return [
            /* Inject script onto RCM pages */
            'Rcm' => [
                'HtmlIncludes' => [
                    'scripts' => [
                        'modules' => [
//                            '/modules/switch-user/dist/switch-user.min.js' => [] //moved to webpack
                        ],
                    ]
                ]
            ],

            /* ASSET MANAGER */
//            'asset_manager' => [ //moved to webpack
//                'resolver_configs' => [
//                    'aliases' => [
//                        'modules/switch-user/' => __DIR__ . '/../public/',
//                    ],
//                ],
//            ],

            /* DOCTRINE */
            'doctrine' => [
                'driver' => [
                    'Rcm\SwitchUser' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/../src/Entity'
                        ]
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'Rcm\SwitchUser' => 'Rcm\SwitchUser'
                        ]
                    ]
                ]
            ],
            /* Rcm\SwitchUser Configuration */
            'Rcm\\SwitchUser' => [
                'restrictions' => [
                    \Rcm\SwitchUser\Restriction\AclRestriction::class,
                    \Rcm\SwitchUser\Restriction\SuUserRestriction::class,
                ],
                /*
                 * 'basic' = no auth required
                 * 'auth'  = password auth required to switch back to admin
                 */
                'switcherMethod' => 'auth',
                /**
                 * register switchers
                 * ['{switcherMethod}' => '{ServiceName}']
                 */
                'switcherServices' => [
                    'basic' => \Rcm\SwitchUser\Switcher\BasicSwitcher::class,
                    'auth' => \Rcm\SwitchUser\Switcher\AuthSwitcher::class,
                ]
            ],

            /* Plugin Config */
            'rcmPlugin' => [
                'RcmSwitchUser' => [
                    'type' => 'Admin',
                    'display' => 'Switch User',
                    'tooltip' => 'Switch User Admin options',
                    'icon' => '',
                    'defaultInstanceConfig' => [
                        'showSwitchToUserNameField' => 'true',
                        'switchToUserNamePlaceholder' => 'Username',
                        'switchToUserNameButtonLabel' => 'Switch to User',
                        'switchBackButtonLabel' => 'End Impersonation',
                        'switchUserInfoContentPrefix' => 'Impersonating:'
                    ],
                    'canCache' => false
                ],
            ],

            'request_context' => [
                'config_factories' => [
                    AssertCurrentUserIsAllowedToSwitchUser::class => [
                        'arguments' => AssertIsAllowed::class
                    ]
                ]
            ],

            /* SERVICE MANAGER */
            'dependencies' => [
                'config_factories' => [
                    AssertImpersonatorIsAllowed::class => [
                        'arguments' => [
                            IsAllowedByUser::class,
                            SwitchUserService::class
                        ]
                    ],
                    AssertImpersonatorOrUserIsAllowed::class => [
                        'arguments' => [
                            RequestContext::class,
                            AssertImpersonatorIsAllowed::class,
                        ]
                    ],
                    DoesAclSayUserCanSU::class => [
                        'arguments' => [
                            IsAllowedByUser::class,
                        ]
                    ],
                    \Rcm\SwitchUser\Restriction\AclRestriction::class => [
                        'arguments' => [
                            DoesAclSayUserCanSU::class,
                        ]
                    ],
                    \Rcm\SwitchUser\Restriction\SuUserRestriction::class => [
                        'arguments' => [
                            DoesAclSayUserCanSU::class,
                        ]
                    ],

                    /* Services */
                    \Rcm\SwitchUser\Service\SwitchUserAclService::class => [
                        'arguments' => [
                            DoesAclSayUserCanSU::class,
                            GetIdentity::class,
                            \Rcm\SwitchUser\Service\SwitchUserService::class,
                        ]
                    ],
                    \Rcm\SwitchUser\Service\SwitchUserLogService::class => [
                        'arguments' => [
                            'Doctrine\ORM\EntityManager',
                        ]
                    ],
                    \Rcm\SwitchUser\Service\SwitchUserService::class => [
                        'arguments' => [
                            GetUserByUsername::class,
                            GetIdentity::class,
                            DoesAclSayUserCanSU::class,
                            \Rcm\SwitchUser\Restriction\Restriction::class,
                            \Rcm\SwitchUser\Switcher\Switcher::class,
                            \Rcm\SwitchUser\Service\SwitchUserLogService::class,
                        ]
                    ],

                    /* Switchers */
                    \Rcm\SwitchUser\Switcher\BasicSwitcher::class => [
                        'arguments' => [
                            SetIdentityInsecure::class,
                            GetIdentity::class,
                            \Rcm\SwitchUser\Service\SwitchUserLogService::class,
                        ]
                    ],
                    \Rcm\SwitchUser\Switcher\AuthSwitcher::class => [
                        'arguments' => [
                            SetIdentityInsecure::class,
                            GetIdentity::class,
                            \Rcm\SwitchUser\Service\SwitchUserLogService::class,
                            Authenticate::class,
                        ]
                    ],
                ],
                'factories' => [
                    /* DEFAULT Switcher*/
                    \Rcm\SwitchUser\Switcher\Switcher::class
                    => \Rcm\SwitchUser\Switcher\SwitcherFactory::class,

                    \Rcm\SwitchUser\Restriction\Restriction::class
                    => \Rcm\SwitchUser\Restriction\CompositeRestrictionFactory::class
                ],
            ],
        ];
    }
}
