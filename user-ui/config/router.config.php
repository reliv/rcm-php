<?php
/**
 * router.config.php
 */
return [
    'routes' => [
        // GENERAL
        /**
         * TEST CONTROLLER - TESTING ONLY
         *
         * @view
         */
        'RcmUser' => [
            'may_terminate' => true,
            'type' => 'segment',
            'options' => [
                'route' => '/rcmusertest',
                'constraints' => [
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                ],
                'defaults' => [
                    'controller' => 'RcmUser\Ui\Controller\UserTestController',
                    'action' => 'index',
                ],
            ],
        ],
        // ADMIN ACL
        /**
         * RcmUserAdminAcl
         * View for creating and editing roles and rule
         *
         */
        'RcmUserAdminAcl' => [
            'may_terminate' => true,
            'type' => 'segment',
            'options' => [
                'route' => '/admin/rcmuser-acl',
                'constraints' => [],
                'defaults' => [
                    'controller' => 'RcmUser\Ui\Controller\AdminAclController',
                    'action' => 'index',
                ],
            ],
        ],
        // ADMIN USERS
        /**
         * RcmUserAdminUsers
         * View for creating and editing users
         */
        'RcmUserAdminUsers' => [
            'may_terminate' => true,
            'type' => 'segment',
            'options' => [
                'route' => '/admin/rcmuser-users',
                'defaults' => [
                    'controller' => 'RcmUser\Ui\Controller\AdminUserController',
                    'action' => 'index',
                ],
            ],
        ],
        /* ADMIN USER ROLES
        'RcmUserAdminUserRole' => [
            'may_terminate' => true,
            'type' => 'segment',
            'options' => [
                'route' => '/admin/rcmuser-user-role/:id',
                'defaults' => [
                    'controller' => 'RcmUser\Ui\Controller\AdminUserRoleController',
                    'action' => 'index',
                ],
            ],
        ],
        */
    ],
];
