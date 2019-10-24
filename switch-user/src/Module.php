<?php

namespace Rcm\SwitchUser;

/**
 * Module Config For ZF2
 */
class Module
{
    public function getConfig()
    {
        $moduleConfig = new ModuleConfig();

        $config = $moduleConfig->__invoke();

        $config['controllers'] = [
            'invokables' => [
                \Rcm\SwitchUser\ApiController\RpcSuController::class
                => \Rcm\SwitchUser\ApiController\RpcSuController::class,
                \Rcm\SwitchUser\ApiController\RpcSwitchBackController::class
                => \Rcm\SwitchUser\ApiController\RpcSwitchBackController::class,
                \Rcm\SwitchUser\Controller\AdminController::class
                => \Rcm\SwitchUser\Controller\AdminController::class,
            ],
        ];

        $config['router'] = [
            'routes' => [
                'Rcm\SwitchUser\ApiController\RpcSu' => [
                    'type' => 'Zend\Mvc\Router\Http\Segment',
                    'options' => [
                        'route' => '/api/rpc/switch-user[/:id]',
                        'defaults' => [
                            'controller' => \Rcm\SwitchUser\ApiController\RpcSuController::class,
                        ]
                    ]
                ],
                'Rcm\SwitchUser\ApiController\RpcSwitchBack' => [
                    'type' => 'Zend\Mvc\Router\Http\Segment',
                    'options' => [
                        'route' => '/api/rpc/switch-user-back[/:id]',
                        'defaults' => [
                            'controller' => \Rcm\SwitchUser\ApiController\RpcSwitchBackController::class,
                        ]
                    ]
                ],
                'Rcm\SwitchUser\Controller\Admin' => [
                    'type' => 'Zend\Mvc\Router\Http\Segment',
                    'options' => [
                        'route' => '/admin/switch-user',
                        'defaults' => [
                            'controller' => \Rcm\SwitchUser\Controller\AdminController::class,
                            'action' => 'index',
                        ]
                    ]
                ],
            ],
        ];

        $config['service_manager'] = $config['dependencies'];

        unset($config['dependencies']);

        $config['view_manager'] = [
            'template_path_stack' => [
                __DIR__ . '/../view',
            ],
        ];

        return $config;
    }
}
