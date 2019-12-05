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
