<?php
/**
 * ZF2 Module Config file for Rcm
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 */
$bowerComponentsDir = __DIR__ . '/../../../../public/bower_components/';
return [
    /* asset_manager */
    'asset_manager' => require __DIR__ . '/asset_manager.php',
    /* controller_plugins */
    'controller_plugins' => [
        'factories' => [
            'shouldShowRevisions'
            => 'Rcm\Factory\ShouldShowRevisionsPluginFactory',
            'rcmIsAllowed' =>
                'Rcm\Factory\RcmIsAllowedFactory',
            'rcmIsSiteAdmin' =>
                'Rcm\Factory\IsSiteAdminPluginFactory',
            'rcmIsPageAllowed' =>
                '\Rcm\Factory\RcmIsPageAllowedPluginFactory',
        ],
        'invokables' => [
            'redirectToPage'
            => 'Rcm\Controller\Plugin\RedirectToPage',
            'urlToPage'
            => 'Rcm\Controller\Plugin\UrlToPage',
        ],
    ],
    /* controllers */
    'controllers' => [
        'abstract_factories' => [
            'Rcm\Factory\AbstractPluginControllerFactory'
        ],
        'config_factories' => [
            'Rcm\Controller\CacheController' => [
                'class' => '\Rcm\Controller\CacheController',
                'arguments' => ['ServiceManager'],
            ],
            'Rcm\Controller\InstanceConfigApiController' => [
                'class' => 'Rcm\Controller\InstanceConfigApiController',
                'arguments' => ['ServiceManager'],
            ],
            'Rcm\Controller\NewPluginInstanceApiController' => [
                'class' => 'Rcm\Controller\NewPluginInstanceApiController',
                'arguments' => ['ServiceManager'],
            ],
            'Rcm\Controller\PageCheckController' => [
                'class' => 'Rcm\Controller\PageCheckController',
                'arguments' => ['ServiceManager'],
            ],
            'Rcm\Controller\PageSearchApiController' => [
                'class' => 'Rcm\Controller\PageSearchApiController',
                'arguments' => ['ServiceManager'],
            ],
        ],
        'factories' => [
            'Rcm\Controller\IndexController' => 'Rcm\Factory\IndexControllerFactory',
            'Rcm\Controller\CmsController' => 'Rcm\Factory\CmsControllerFactory',
        ],
    ],
    /* doctrine */
    'doctrine' => [
        'driver' => [
            'relivContentManager' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    'Rcm' => 'relivContentManager'
                ]
            ]
        ],
        'configuration' => [
            'orm_default' => [
                'metadata_cache' => 'doctrine_cache',
                'query_cache' => 'doctrine_cache',
                'result_cache' => 'doctrine_cache',
            ]
        ],
    ],
    /* Rcm Config */
    'Rcm' => require __DIR__ . '/rcm-core-config.php',
    /* rcmCache */
    'rcmCache' => [
        'adapter' => 'Memory',
        'plugins' => [],
        'options' => [ //'namespace' => 'RcmCache'
        ]
    ],
    /* RcmUser Config */
    'RcmUser' => [
        'Acl\Config' => [
            'ResourceProviders' => [
                'Rcm\Acl\ResourceProvider' => 'Rcm\Acl\ResourceProvider',
            ],
        ],
    ],
    /* route_manager */
    'route_manager' => [
        'invokables' => [
            'Rcm\Route\Cms' => 'Rcm\Route\Cms'
        ],
    ],
    /* router */
    'router' => require __DIR__ . '/router.php',
    /* service_manager */
    'service_manager' => require __DIR__ . '/service_manager.php',
    /* view_helpers */
    'view_helpers' => [
        'factories' => [
            'rcmContainer'
            => 'Rcm\Factory\ContainerViewHelperFactory',
            'rcmTextEdit' => 'Rcm\Factory\TextEditFactory',
            'rcmRichEdit' => 'Rcm\Factory\RichEditFactory',
            'rcmHtmlIncludes' => 'Rcm\Factory\RcmHtmlIncludesHelperFactory',
        ],
        'invokables' => [
            /* <OVER-RIDE ZF2 HELPERS> */
            'headmeta'
            => 'Rcm\View\Helper\HeadMeta',
            'headtitle'
            => 'Rcm\View\Helper\HeadTitle',
            'headlink'
            => 'Rcm\View\Helper\HeadLink',
            /* </OVER-RIDE ZF2 HELPERS> */
            'rcmOutOfDateBrowserWarning'
            => 'Rcm\View\Helper\OutOfDateBrowserWarning',
            'urlToPage'
            => 'Rcm\View\Helper\UrlToPage',
            'revisionHelper'
            => 'Rcm\View\Helper\RevisionHelper',

        ],
    ],
    /* view_manager */
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
