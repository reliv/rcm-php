<?php
/**
 * Module Config For ZF2
 */

namespace RcmUser\Api;

/**
 * Class Module
 */
class Module
{
    public function getConfig()
    {
        return [
            /* Controllers */
            'controllers' => [
                'config_factories' => [
                    /* ADMIN ACL */
                    \RcmUser\Api\Controller\AclDefaultUserRoleController::class => [
                        'class' => \RcmUser\Api\Controller\AclDefaultUserRoleController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                    \RcmUser\Api\Controller\AclResourcesController::class => [
                        'class' => \RcmUser\Api\Controller\AclResourcesController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                    \RcmUser\Api\Controller\AclRoleController::class => [
                        'class' => \RcmUser\Api\Controller\AclRoleController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                    \RcmUser\Api\Controller\AclRulesByRolesController::class => [
                        'class' => \RcmUser\Api\Controller\AclRulesByRolesController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                    \RcmUser\Api\Controller\AclRuleController::class => [
                        'class' => \RcmUser\Api\Controller\AclRuleController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                    /* ADMIN USERS */
                    \RcmUser\Api\Controller\UserAdminController::class => [
                        'class' => \RcmUser\Api\Controller\UserAdminController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                    \RcmUser\Api\Controller\UserValidUserStatesController::class => [
                        'class' => \RcmUser\Api\Controller\UserValidUserStatesController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                    /* ADMIN USER ROLES */
                    \RcmUser\Api\Controller\DefaultRoleDataController::class => [
                        'class' => \RcmUser\Api\Controller\DefaultRoleDataController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                    \RcmUser\Api\Controller\UserRolesController::class => [
                        'class' => \RcmUser\Api\Controller\UserRolesController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                    \RcmUser\Api\Controller\UserRoleController::class => [
                        'class' => \RcmUser\Api\Controller\UserRoleController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                    \RcmUser\Api\Controller\UserController::class => [
                        'class' => \RcmUser\Api\Controller\UserController::class,
                        'arguments' => ['ServiceManager'],
                    ],
                ],
            ],
            /**
             * Configuration
             */
            'RcmUser\\Api' => [

            ],
            /**
             * Router
             */
            'router' => [
                'routes' => [
                    // ADMIN ACL
                    /**
                     * RcmUser\Api\AclResources
                     * Get resources
                     *
                     * @api
                     */
                    'RcmUser\Api\AclResources' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/admin/rcmuser-acl-resources[/:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\AclResourcesController::class,
                            ],
                        ],
                    ],
                    /**
                     * RcmUser\Api\AclRulesByRoles
                     * Returns Roles and the related Rules
                     *
                     * @api
                     */
                    'RcmUser\Api\AclRulesByRoles' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/admin/rcmuser-acl-rulesbyroles[/:id]',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\AclRulesByRolesController::class,
                            ],
                        ],
                    ],
                    /**
                     * RcmUser\Api\AclRule
                     * Return rules and exposes create and delete
                     *
                     * @api
                     */
                    'RcmUser\Api\AclRule' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/admin/rcmuser-acl-rule[/:id]',
                            //'constraints' => [
                            //'id' => '[a-zA-Z0-9_-]+',
                            //],
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\AclRuleController::class,
                            ],
                        ],
                    ],
                    /**
                     * RcmUser\Api\AclRole
                     * Return roles and exposes create and delete
                     *
                     * @api
                     */
                    'RcmUser\Api\AclRole' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/admin/rcmuser-acl-role[/:id]',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]+',
                            ],
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\AclRoleController::class,
                            ],
                        ],
                    ],
                    /**
                     * RcmUser\Api\AclDefaultUserRole
                     * Return default User roles
                     *
                     * @api
                     */
                    'RcmUser\Api\AclDefaultUserRole' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/admin/rcmuser-acl-default-user-roles[/:id]',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]+',
                            ],
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\AclDefaultUserRoleController::class,
                            ],
                        ],
                    ],
                    // ADMIN USERS
                    /**
                     * RcmUser\Api\User
                     * API for creating and editing users
                     *
                     * @api
                     */
                    'RcmUser\Api\User' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/admin/rcmuser-user[/:id]',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]+',
                            ],
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\UserAdminController::class,
                            ],
                        ],
                    ],
                    /**
                     * RcmUser\Api\UserValidUserStates
                     * API to get list of valid user states
                     *
                     * @api
                     */
                    'RcmUser\Api\UserValidUserStates' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/admin/rcmuser-user-validuserstates[/:id]',
                            'constraints' => [
                                'id' => '[a-zA-Z0-9_-]+',
                            ],
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\UserValidUserStatesController::class,
                            ],
                        ],
                    ],
                    // User Roles
                    'RcmUser\Api\DefaultRoleData' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/admin/default-role-data[/:id]',
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\DefaultRoleDataController::class,
                            ],
                        ],
                    ],

                    /**
                     * RcmUser\Api\UserRoles
                     * API for listing, creating and deleting user roles as a group
                     *
                     * @api
                     */
                    'RcmUser\Api\UserRoles' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/admin/rcmuser-user-roles[/:id]',
                            //'constraints' => [
                            //'id' => '[a-zA-Z0-9_-]+',
                            //],
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\UserRolesController::class,
                            ],
                        ],
                    ],
                    /**
                     * RcmUser\Api\UserRole
                     * API creating and deleting an individual user role
                     *
                     * @api
                     */
                    'RcmUser\Api\UserRole' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/admin/rcmuser-user-role[/:id]',
                            //'constraints' => [
                            //'id' => '[a-zA-Z0-9_-]+',
                            //],
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\UserRoleController::class,
                            ],
                        ],
                    ],
                    /**
                     * RcmUserApiUser
                     * API for User
                     * - GET New:     /api/rcm-user/user/new
                     * - GET current: /api/rcm-user/user/current
                     * - POST login:  /api/rcm-user/user/login
                     *   {
                     *    "username": "MYUSERNAME",
                     *    "password": "MYPASSWORD"
                     *   }
                     * - POST logout: /api/rcm-user/user/logout
                     *
                     * @api
                     */
                    'RcmUserApiUser' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/api/rcm-user/user[/:id]',
                            'defaults' => [
                                'controller' =>
                                    \RcmUser\Api\Controller\UserController::class,
                            ],
                        ],
                    ],
                ],
            ],
            /**
             *ServiceManager
             */
            'service_manager' => [
                'factories' => [

                ]
            ],
        ];
    }
}
