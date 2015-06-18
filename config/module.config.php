<?php

/**
 * ZF2 Module Config file for Rcm
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

return [

    'Rcm' => [
        'defaultDomain' => null,
        /**
         * Available page types
         */
        'pageTypes' => [
            'n' => [
                'type' => 'n',
                'title' => 'Normal Page',
                'canClone' => true,
            ],
            't' => [
                'type' => 't',
                'title' => 'Template Page',
                'canClone' => true,
            ],
            'z' => [
                'type' => 'z',
                'title' => 'System Page',
                'canClone' => true,
            ],
        ],
        'successfulLoginUrl' => '/',
        'Acl' => [
            'sites' => [
                'resourceId' => 'sites',
                'parentResourceId' => null,
                'privileges' => [
                    'read',
                    'update',
                    'create',
                    'delete',
                    'theme',
                    'admin',
                ],
                'name' => 'Sites',
                'description' => 'Global resource for sites',
            ],
            'pages' => [
                'resourceId' => 'pages',
                'parentResourceId' => null,
                'privileges' => [
                    'read',
                    'edit',
                    'create',
                    'delete',
                    'copy',
                    'approve',
                    'layout',
                    'revisions'
                ],
                'name' => 'Pages',
                'description' => 'Global resource for pages',
            ],
            'widgets' => [
                'resourceId' => 'widgets',
                'parentResourceId' => null,
                'privileges' => [
                    'update',
                ],
                'name' => 'Widgets',
                'description' => 'Global resource for Rcm Widgets',
            ],
            'widgets.siteWide' => [
                'resourceId' => 'widgets.siteWide',
                'parentResourceId' => 'widgets',
                'privileges' => [
                    'update',
                    'create',
                    'delete',
                ],
                'name' => 'Sitewide Widgets',
                'description' => 'Global resource for Rcm Site Wide Widgets',
            ],
        ],
        'RcmCmsPageRouteNames' => [
            'contentManager' => 'contentManager',
            'contentManagerWithPageType' => 'contentManagerWithPageType',
            'blog' => 'blog',
        ],
        /**
         * Scripts to be required always
         */
        'HtmlIncludes' => [
            /* Meta tags that will always be loaded
            Example
            'keyValue' => [
                'content' => 'value',
                'modifiers' => [],
            ],
             */
            'headMetaName' => [
                'X-UA-Compatible' => [
                    'content' => 'IE=edge',
                ],
                'viewport' => [
                    'content' => 'width=device-width, initial-scale=1',
                ],
            ],
            /* Script files that will always be loaded
            Example
            '/script/url' => [
                'type' => 'text/javascript',
                'attrs' => []
            ],
             */
            'headScriptFile' => [
                '/vendor/es5-shim/es5-shim.min.js' => [
                    'type' => 'text/javascript',
                    'attrs' => [
                        'conditional' => 'lt IE 9'
                    ]
                ],
                '/modules/rcm-jquery/jquery-ui-1.10.4.custom/js/jquery-1.10.2.min.js' => [],
                // @todo Move this config to the modules that use it
                '/modules/rcm-jquery/jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.min.js' => [],
                '/modules/rcm-angular-js/angular/angular.js' => [],
                '/vendor/bootbox/bootbox.js' => [],
                '/vendor/bootstrap/dist/js/bootstrap.min.js' => [],
                '/modules/rcm-tinymce-js/tinymce/tinymce.min.js' => [],

                '/modules/rcm/rcm.js' => [],
                '/vendor/rcm-dialog/dist/rcm-dialog.min.js' => [],
                '/vendor/rcm-html-editor/dist/adapter-tinymce/rcm-html-editor.min.js' => [],
                '/vendor/rcm-html-editor/dist/rcm-html-editor.min.js' => [],
                '/modules/rcm/modules.js' => [],
            ],
            /* Stylesheet files that will always be loaded
            Example
            '/stylesheet/url' => [
                'media' => 'screen',
                'conditionalStylesheet' => '',
                'extras' => []
            ],
             */
            'headLinkStylesheet' => [
                '/vendor/bootstrap/dist/css/bootstrap.min.css' => [],
                // @todo Move this config to the modules that use it
                '/modules/rcm-jquery/jquery-ui-1.10.4.custom/css/smoothness/jquery-ui-1.10.4.custom.min.css' => [],
                '/modules/rcm/rcm.css' => [],
                '/vendor/rcm-html-editor/dist/adapter-tinymce/rcm-html-editor.min.css',
                '/modules/rcm/modules.css' => [],
            ],
        ]
    ],
    'RcmUser' => [
        'Acl\Config' => [
            'ResourceProviders' => [
                'Rcm\Acl\ResourceProvider' => 'Rcm\Acl\ResourceProvider',
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'router' => [
        'routes' => [
            'api-admin-instance-configs' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/admin/instance-configs/:pluginType/:id',
                    'defaults' => [
                        'controller' => 'Rcm\Controller\InstanceConfigApiController',
                    ]
                ],
            ],
            'contentManager' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm[/:page][/:revision]',
                    'defaults' => [
                        'controller' => 'Rcm\Controller\IndexController',
                        'action' => 'index',
                    ]
                ],
            ],
            'contentManagerNewInstanceAjax' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-admin-get-instance/:pluginType/:instanceId',
                    'defaults' => [
                        'controller' => 'Rcm\Controller\NewPluginInstanceApiController',
                        'action' => 'getNewInstance',
                    ],
                ],
            ],
            'contentManagerWithPageType' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm/:pageType/:page[/:revision]',
                    'constraints' => [
                        'pageType' => '[a-z]',
                    ],
                    'defaults' => [
                        'controller' => 'Rcm\Controller\IndexController',
                        'action' => 'index',
                    ]
                ],
            ],
            'rcm-page-title-search' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-page-search/title[/:query]',
                    'defaults' => [
                        'controller' => 'Rcm\Controller\PageSearchApiController',
                        'action' => 'siteTitleSearch',
                    ]
                ],
            ],
            'rcm-page-search' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/rcm-page-search[/:language]',
                    'defaults' => [
                        'controller' => 'Rcm\Controller\PageSearchApiController',
                        'action' => 'allSitePages',
                    ]
                ],
            ],
            'load-balancer-health-check' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/load-balancer-health-check',
                    'defaults' => [
                        'controller' => 'Rcm\Controller\PageSearchApiController',
                        'action' => 'allSitePages',
                    ]
                ],
            ],
            'blog' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/blog[/:page]',
                    'defaults' => [
                        'controller' => 'Rcm\Controller\IndexController',
                        'action' => 'index',
                    ]
                ],
            ],
            'Rcm\Api\Page\Check' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/rcm/page/check[/:pageType]/:pageId',
                    'defaults' => [
                        'controller' => 'Rcm\Controller\PageCheckController',
                    ],
                ],
            ],
            'Rcm\Cache\Flush' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route' => '/rcm/cache/flush',
                    'defaults' => [
                        'controller' => '\Rcm\Controller\CacheController',
                        'action' => 'flush',
                    ],
                ],
            ]

        ],
    ],
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
    'rcmCache' => [
        'adapter' => 'Memory',
        'plugins' => [],
        'options' => [ //'namespace' => 'RcmCache'
        ]
    ],
    'service_manager' => [
        'factories' => [
            'doctrine.cache.doctrine_cache'
            => 'Rcm\Factory\DoctrineCacheFactory',
            'Rcm\EventListener\EventWrapper'
            => 'Rcm\Factory\EventWrapperFactory',
            'Rcm\EventListener\RouteListener'
            => 'Rcm\Factory\RouteListenerFactory',
            'Rcm\EventListener\DispatchListener'
            => 'Rcm\Factory\DispatchListenerFactory',
            'Rcm\EventListener\EventFinishListener'
            => 'Rcm\Factory\EventFinishListenerFactory',
            'Rcm\EventListener\ViewEventListener'
            => 'Rcm\Factory\ViewEventListenerFactory',
            'Rcm\Service\PluginManager'
            => 'Rcm\Factory\PluginManagerFactory',
            'Rcm\Service\LayoutManager'
            => 'Rcm\Factory\LayoutManagerFactory',
            'Rcm\Service\ResponseHandler'
            => 'Rcm\Factory\ResponseHandlerFactory',
            'Rcm\Service\Cache'
            => 'Rcm\Factory\CacheFactory',
            'Rcm\Service\AssetManagerCache'
            => 'Rcm\Factory\AssetManagerCacheFactory',
            'Rcm\Service\SessionMgr'
            => 'Rcm\Factory\SessionManagerFactory',
            'Rcm\Acl\ResourceProvider'
            => 'Rcm\Factory\AclResourceProviderFactory',
            'Rcm\Validator\Page'
            => 'Rcm\Factory\PageValidatorFactory',
            'Rcm\Validator\PageTemplate'
            => 'Rcm\Factory\PageTemplateFactory',
            'Rcm\Validator\MainLayout'
            => 'Rcm\Factory\MainLayoutValidatorFactory',
            'Rcm\Service\Logger'
            => 'Rcm\Factory\LoggerFactory',
            'Rcm\Service\ZendLogger'
            => '\Rcm\Factory\ZendLogFactory',
            'Rcm\Service\ZendLogWriter'
            => '\Rcm\Factory\ZendLogWriterFactory',
            'Rcm\Acl\CmsPermissionsChecks'
            => '\Rcm\Factory\CmsPermissionsChecksFactory',
            'Rcm\Service\CurrentSite'
            => '\Rcm\Factory\CurrentSiteFactory',
        ],
        'invokables' => [
            'Rcm\Service\DisplayCountService' => 'Rcm\Service\DisplayCountService'
        ],
        'aliases' => [
            'rcmLogger' => 'Rcm\Service\Logger',
        ]
    ],
    'controllers' => [
        'abstract_factories' => [
            'Rcm\Factory\AbstractPluginControllerFactory'
        ],
        'invokables' => [
            'Rcm\Controller\PageCheckController'
            => 'Rcm\Controller\PageCheckController',
            'Rcm\Controller\InstanceConfigApiController'
            => 'Rcm\Controller\InstanceConfigApiController',
            'Rcm\Controller\PageSearchApiController'
            => 'Rcm\Controller\PageSearchApiController',
            'Rcm\Controller\NewPluginInstanceApiController'
            => 'Rcm\Controller\NewPluginInstanceApiController',
            'Rcm\Controller\CacheController' => '\Rcm\Controller\CacheController'
        ],
        'factories' => [
            'Rcm\Controller\IndexController'
            => 'Rcm\Factory\IndexControllerFactory',
        ],
    ],
    'view_helpers' => [
        'factories' => [
            'rcmContainer'
            => 'Rcm\Factory\ContainerViewHelperFactory',
            'rcmTextEdit' => 'Rcm\Factory\TextEditFactory',
            'rcmRichEdit' => 'Rcm\Factory\RichEditFactory',
            'rcmHtmlIncludes'  => 'Rcm\Factory\RcmHtmlIncludesHelperFactory',
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
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm/' => __DIR__ . '/../public/',
                // Global JS path for dependencies
                'vendor/' => __DIR__ . '/../../vendor/bower-asset',
            ],
            'collections' => [
                /**
                 * Core JS and css
                 * (core features)
                 */
                'modules/rcm/rcm.js' => [
                    'vendor/rcm-js-lib/dist/rcm-js-lib.min.js',
                    'vendor/rcm-loading/dist/rcm-loading.min.js',
                    'vendor/rcm-loading/dist/angular-rcm-loading.min.js',
                    'vendor/rcm-loading/dist/jquery-loader.min.js',

                    'modules/rcm/core/rcm.js',
                    'modules/rcm/core/rcm-api.js',
                    'modules/rcm/core/rcm-form-double-submit-protect.js',
                    'modules/rcm/core/rcm-bootstrap-alert-confirm.js',
                    'modules/rcm/core/rcm-popout-window.js',
                ],
                'modules/rcm/rcm.css' => [
                    'modules/rcm/core/rcm.css',
                ],
                /**
                 * Extended JS and css
                 * (features for modules and lower level services)
                 */
                'modules/rcm/modules.js' => [],
                'modules/rcm/modules.css' => [],
            ],
        ],
    ],
];
