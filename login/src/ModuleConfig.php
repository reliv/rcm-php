<?php

namespace RcmLogin;

use Rcm\HttpLib\JsonBodyParserMiddleware;
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
                    'middleware' => [
                        JsonBodyParserMiddleware::class,
                        LoginFormSubmitHandler::class
                    ],
                    'allowed_methods' => ['POST'],
                ],
            ]
        ];
    }
}
