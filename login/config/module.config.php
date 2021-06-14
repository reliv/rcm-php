<?php

return [
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-login/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/admin.js' => [
                    'modules/rcm-login/rcm-login-edit.js',
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'RcmLogin' => 'RcmLogin\Factory\PluginControllerFactory',
        ],
    ],
    'doctrine' => [
        'driver' => [
            'RcmLogin' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    'RcmLogin' => 'RcmLogin'
                ]
            ]
        ]
    ],
    'rcmPlugin' => [
        'RcmLogin' => [
            'type' => 'Common',
            'display' => 'Login Area',
            'tooltip' => 'Adds login area to page',
            'icon' => '',
            'requireHttps' => true,
            'defaultInstanceConfig' => include __DIR__ .
                '/defaultInstanceConfig.php',
            'canCache' => false,
            'redirectBlacklistPattern' => '/.+:\/\/|\/\//i',
            'csrfTimeoutSeconds' => 60 * 60 * 24 * 30 // thirty days worth of seconds
        ],
    ],
    'service_manager' => [
        'factories' => [
            'RcmLogin\EventListener\Login'
            => 'RcmLogin\Factory\LoginEventListenerFactory',

            'RcmLogin\Validator\RedirectValidator'
            => 'RcmLogin\Factory\RedirectValidatorFactory',

            'RcmLogin\Filter\RedirectFilter'
            => 'RcmLogin\Factory\RedirectFilterFactory',

            'RcmLogin\Email\DefaultMailer'
            => 'RcmLogin\Factory\DefaultMailerFactory',

            /* over-ride this for logging */
            \RcmLogin\Log\Logger::class
            => \RcmLogin\Log\LoggerNoneFactory::class,

            'Rcmlogin\Validator\Csrf' => \RcmLogin\Factory\CsrfValidatorFactory::class
        ],
        'invokables' => [
            'RcmLogin\Form\LabelHelper' => 'RcmLogin\Form\LabelHelper',
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
