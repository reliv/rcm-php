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
                    __DIR__ . '/../src/Rcm/Entity'
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
        'abstract_factories' => [
            'Rcm\Factory\AbstractPluginControllerFactory'
        ],
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
        'aliases' => [
            'rcmLogger' => 'Rcm\Service\Logger',
        ]
    ],
    'controllers' => [
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
        ],
        'invokables' => [
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
            ],
        ],
    ],
];
