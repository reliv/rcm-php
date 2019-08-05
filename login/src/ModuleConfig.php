<?php

namespace RcmLogin;

use RcmLogin\Controller\LoginFormSubmitHandler;
use RcmUser\Service\RcmUserService;

class ModuleConfig
{

    public function __invoke()
    {
        return [
            'dependencies' => [
                'config_factories' => [
                    LoginFormSubmitHandler::class => [
                        'arguments' => [
                            RcmUserService::class,
                            'EventManager',
                            'Rcmlogin\Validator\Csrf'
                        ]
                    ]
                ]
            ],
            'routes' => [
                [
                    'path' => '/rcm-login/login-form-submit-handler',
                    'middleware' => LoginFormSubmitHandler::class,
                    'allowed_methods' => ['POST'],
                ],
            ]
        ];
    }
}
