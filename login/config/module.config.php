<?php

/**
 * ZF2 Plugin Config file
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

$mergedResetPassword = array_merge(
    require(__DIR__ . '/createPasswordDefaultInstanceConfig.php'),
    require(__DIR__ . '/resetPasswordDefaultInstanceConfig.php')
);

return [
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-login/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/admin.js' => [
                    'modules/rcm-login/rcm-login-edit.js',
                    'modules/rcm-login/rcm-reset-password-edit.js',
                    'modules/rcm-login/rcm-create-new-password-edit.js',
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'RcmLogin' => 'RcmLogin\Factory\PluginControllerFactory',
            'RcmResetPassword' => 'RcmLogin\Factory\ResetPasswordPluginControllerFactory',
            'RcmCreateNewPassword' => 'RcmLogin\Factory\CreatePasswordPluginControllerFactory',
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
        'RcmResetPassword' => [
            'type' => 'Common',
            'display' => 'Reset Password',
            'tooltip' => 'Reset Password',
            'icon' => '',
            'defaultInstanceConfig' => $mergedResetPassword,
            'canCache' => false,
            'mailer' => 'RcmLogin\Email\DefaultMailer',
        ],
        /**
         * @deprecated - The RcmResetPassword plugin now handles both the first and second
         * page of the password reset process. The 2nd page will be rendered if
         * fromPasswordResetEmail=1 is in the url. This one done to reduce the number of pages
         * needed for this process to reduce brittleness.
         */
        'RcmCreateNewPassword' => [
            'type' => 'Common',
            'display' => 'Create New Password',
            'tooltip' => 'Create New Password',
            'icon' => '',
            'defaultInstanceConfig' => $mergedResetPassword,
            'canCache' => false,
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

            \RcmLogin\InputFilter\CreateNewPasswordInputFilter::class
            => \RcmLogin\Factory\CreateNewPasswordInputFilterFactory::class,

            \RcmLogin\InputFilter\ResetPasswordInputFilter::class
            => \RcmLogin\Factory\ResetPasswordInputFilterFactory::class,

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
