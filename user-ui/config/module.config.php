<?php
return [
    /**
     * Config for allowing dynamic loading of public assets
     */
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-user/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-user/core.js' => [
                    'modules/rcm-user/core/module.js',
                    'modules/rcm-user/core/cache.js',
                    'modules/rcm-user/core/event-manager.js',
                    'modules/rcm-user/core/namespace-repeat-string.js',
                    'modules/rcm-user/core/rcm-user-safe-apply.js',
                    'modules/rcm-user/core/rcm-user.js',
                    'modules/rcm-user/core/rcm-user-config.js',
                    'modules/rcm-user/core/rcm-user-http.js',
                    'modules/rcm-user/core/rcm-user-result.js',
                    'modules/rcm-user/core/rcm-user-results.js',
                    'modules/rcm-user/core/rcm-alerts-directive.js',
                    'modules/rcm-user/core/rcm-user-loading-directive.js',
                    'modules/rcm-user/core/rcm-user-global.js',
                    'modules/rcm-user/core/rcm-user-selected-data-service.js',
                    'modules/rcm-user/core/rcm-user-user-service.js',
                    'modules/rcm-user/core/rcm-user-acl-resource-service.js',
                    'modules/rcm-user/core/rcm-user-acl-role-service.js',
                    'modules/rcm-user/core/rcm-user-acl-rule-service.js',

                ],
                'modules/rcm-user/admin-acl-app.js' => [
                    'modules/rcm-user/admin-acl-app/module.js',
                    'modules/rcm-user/admin-acl-app/rcmuser-admin-acl-data.js',
                    'modules/rcm-user/admin-acl-app/rcmuser-admin-acl-add-rule-directive.js',
                    'modules/rcm-user/admin-acl-app/rcmuser-admin-acl-add-role-directive.js',
                    'modules/rcm-user/admin-acl-app/rcmuser-admin-acl-roles-controller.js',
                    'modules/rcm-user/admin-acl-app/rcmuser-admin-acl-remove-role-directive.js',
                    'modules/rcm-user/admin-acl-app/rcmuser-admin-acl-remove-rule-directive.js',
                    'modules/rcm-user/admin-acl-app/resource-filter.js',
                ],
                'modules/rcm-user/admin-user-role-app.js' => [
                    'modules/rcm-user/admin-user-role-app/module.js',
                    'modules/rcm-user/admin-user-role-app/rcmuser-admin-user-role.js',
                ],
                'modules/rcm-user/admin-users-app.js' => [
                    'modules/rcm-user/admin-users-app/module.js',
                    'modules/rcm-user/admin-users-app/rcmuser-admin-user-controller.js',
                    'modules/rcm-user/admin-users-app/rcmuser-admin-users-controller.js',
                    'modules/rcm-user/admin-users-app/rcmuser-admin-users-data.js',
                    'modules/rcm-user/admin-users-app/user-filter.js',
                ],
                'modules/rcm-user/rcm-user-roles-service.js' => [
                    'modules/rcm-user/rcm-user-roles-service/module.js',
                    'modules/rcm-user/rcm-user-roles-service/rcm-user-roles-service.js',
                    'modules/rcm-user/rcm-user-roles-service/rcm-user-roles-service-global.js',
                ],
                'modules/rcm-user/rcm-user-role-selector.js' => [
                    'modules/rcm-user/rcm-user-role-selector/module.js',
                    'modules/rcm-user/rcm-user-role-selector/rcm-user-role-filter.js',
                    'modules/rcm-user/rcm-user-role-selector/rcm-user-role-selector-directive.js',
                ],
                'modules/rcm-user/rcm-user-role-selector.css' => [
                    'modules/rcm-user/rcm-user-role-selector/rcm-user-role-selector.css',
                ],
                'modules/rcm-user/admin.css' => [
                    'modules/rcm-user/admin/rcm-user-admin.css',
                ],
            ],
        ],
    ],
    /**
     * Controllers
     */
    'controllers' => [
        'invokables' => [
            // TESTING
            'RcmUser\Ui\Controller\UserTestController' =>
                'RcmUser\Ui\Controller\UserTestController',
            // ADMIN ACL
            'RcmUser\Ui\Controller\AdminAclController' =>
                'RcmUser\Ui\Controller\AdminAclController',
            // ADMIN USERS
            'RcmUser\Ui\Controller\AdminUserController' =>
                'RcmUser\Ui\Controller\AdminUserController',
            // ADMIN USER ROLES
            /*'RcmUser\Ui\Controller\AdminUserRoleController'
            => 'RcmUser\Ui\Controller\AdminUserRoleController',*/
        ],
    ],
    /**
     * Controller Plugins
     */
    'controller_plugins' => [
        'factories' => [
            'rcmUserIsAllowed' =>
                'RcmUser\Ui\Controller\Plugin\Factory\RcmUserIsAllowed',
            'rcmUserHasRoleBasedAccess' =>
                'RcmUser\Ui\Controller\Plugin\Factory\RcmUserHasRoleBasedAccess',
            'rcmUserGetCurrentUser' =>
                'RcmUser\Ui\Controller\Plugin\Factory\RcmUserGetCurrentUser',
        ],
    ],
    /**
     * Set for rcm-admin module
     */
    'navigation' => [
        'RcmAdminMenu' => [
            'User' => [
                'pages' => [
                    'RolesAndAccess' => [
                        //'class'  => 'RcmAdminMenu RcmBlankIframeDialog',
                        'label' => 'Roles and Access',
                        'uri' => '/admin/rcmuser-acl',
                    ],
                    'UserManagement' => [
                        //'class'  => 'RcmAdminMenu RcmBlankIframeDialog',
                        'label' => 'User Management',
                        'uri' => '/admin/rcmuser-users',
                    ],
                ],
            ],
        ],
    ],
    /**
     * Configuration
     */
    'RcmUser\\Ui' => [
        /**
         * Include any paths for JavaScript and CSS here
         * The included views require:
         * - AngularJS
         * - Angular-UI
         * - TwitterBootstrap
         */
        'htmlAssets' => [
            'js' => [
                // Expect Angular from RCM core
                // '/vendor/angular/angular.js',
                '/vendor/bootstrap/dist/js/bootstrap.min.js',
                //'/vendor/angular-bootstrap/ui-bootstrap.min.js',
                //'/vendor/angular-bootstrap/ui-bootstrap-tpls.min.js',
            ],

            'css' => [
                '/vendor/bootstrap/dist/css/bootstrap.min.css',
            ],
        ],
    ],
    /**
     * Router
     */
    'router' => require __DIR__ . '/router.config.php',
    /**
     * Service Manager
     */
    'service_manager' => [
        'factories' => [
            'RcmUser\Ui\Service\RcmUserHtmlService'
            => 'RcmUser\Ui\Factory\RcmUserHtmlServiceFactory'
        ]
    ],
    /**
     * View Helpers
     */
    'view_helpers' => [
        'factories' => [
            'rcmUserIsAllowed' =>
                'RcmUser\Ui\View\Helper\Factory\RcmUserIsAllowed',
            'rcmUserHasRoleBasedAccess' =>
                'RcmUser\Ui\View\Helper\Factory\RcmUserHasRoleBasedAccess',
            'rcmUserBuildHtmlHead' =>
                'RcmUser\Ui\View\Helper\Factory\RcmUserBuildHtmlHead',
            'rcmUserGetCurrentUser' =>
                'RcmUser\Ui\View\Helper\Factory\RcmUserGetCurrentUser',
        ],
    ],
    /**
     * View Manager
     */
    'view_manager' => [
        'template_path_stack' => [
            'RcmUser' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
