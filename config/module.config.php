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

return array(

    'Rcm' => array(
        'defaultDomain' => null,
        'successfulLoginUrl' => '/',
        'Acl' => array(
            'sites' => array(
                'resourceId' => 'sites',
                'parentResourceId' => null,
                'privileges' => array(
                    'read',
                    'update',
                    'create',
                    'delete',
                    'theme',
                    'admin',
                ),
                'name' => 'Sites',
                'description' => 'Global resource for sites',
            ),
            'pages' => array(
                'resourceId' => 'pages',
                'parentResourceId' => null,
                'privileges' => array(
                    'read',
                    'edit',
                    'create',
                    'delete',
                    'copy',
                    'approve',
                    'layout',
                    'revisions'
                ),
                'name' => 'Pages',
                'description' => 'Global resource for pages',
            ),
            'widgets' => array(
                'resourceId' => 'widgets',
                'parentResourceId' => null,
                'privileges' => array(
                    'update',
                ),
                'name' => 'Widgets',
                'description' => 'Global resource for Rcm Widgets',
            ),
            'widgets.siteWide' => array(
                'resourceId' => 'widgets.siteWide',
                'parentResourceId' => 'widgets',
                'privileges' => array(
                    'update',
                    'create',
                    'delete',
                ),
                'name' => 'Sitewide Widgets',
                'description' => 'Global resource for Rcm Site Wide Widgets',
            ),
        ),
        'RcmCmsPageRouteNames' => array(
            'contentManager' => 'contentManager',
            'contentManagerWithPageType' => 'contentManagerWithPageType',
            'blog' => 'blog',
        )
    ),
    'RcmUser' => array(
        'Acl\Config' => array(
            'ResourceProviders' => array(
                'Rcm\Acl\ResourceProvider' => 'Rcm\Acl\ResourceProvider',
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'router' => array(
        'routes' => array(
            'api-admin-instance-configs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/api/admin/instance-configs/:pluginType/:id',
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\InstanceConfigApiController',
                    )
                ),
            ),
            'contentManager' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm[/:page][/:revision]',
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\IndexController',
                        'action' => 'index',
                    )
                ),
            ),
            'contentManagerNewInstanceAjax' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-admin-get-instance/:pluginType/:instanceId',
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\NewPluginInstanceApiController',
                        'action' => 'getNewInstance',
                    ),
                ),
            ),
            'contentManagerWithPageType' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm/:pageType/:page[/:revision]',
                    'constraints' => array(
                        'pageType' => '[a-z]',
                    ),
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\IndexController',
                        'action' => 'index',
                    )
                ),
            ),
            'rcm-page-title-search' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-page-search/title[/:query]',
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\PageSearchApiController',
                        'action' => 'siteTitleSearch',
                    )
                ),
            ),
            'rcm-page-search' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-page-search[/:language]',
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\PageSearchApiController',
                        'action' => 'allSitePages',
                    )
                ),
            ),
            'blog' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/blog[/:page]',
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\IndexController',
                        'action' => 'index',
                    )
                ),
            ),
            'Rcm\Api\Page\Check' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/rcm/page/check[/:pageType]/:pageId',
                    'defaults' => array(
                        'controller' => 'Rcm\Controller\PageCheckController',
                    ),
                ),
            ),

        ),
    ),
    'doctrine' => array(
        'driver' => array(

            'relivContentManager' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Rcm/Entity'
                )
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Rcm' => 'relivContentManager'
                )
            )
        ),
        'configuration' => array(
            'orm_default' => array(
                'metadata_cache' => 'doctrine_cache',
                'query_cache' => 'doctrine_cache',
                'result_cache' => 'doctrine_cache',
            )
        ),
    ),
    'rcmCache' => array(
        'adapter' => 'Memory',
        'plugins' => array(),
        'options' => array( //'namespace' => 'RcmCache'
        )
    ),
    'service_manager' => array(
        'factories' => array(
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
        ),
        'aliases' => array(
            'rcmLogger' => 'Rcm\Service\Logger',
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'Rcm\Controller\PageCheckController'
            => 'Rcm\Controller\PageCheckController',
            'Rcm\Controller\InstanceConfigApiController'
            => 'Rcm\Controller\InstanceConfigApiController',
            'Rcm\Controller\PageSearchApiController'
            => 'Rcm\Controller\PageSearchApiController',
            'Rcm\Controller\NewPluginInstanceApiController'
            => 'Rcm\Controller\NewPluginInstanceApiController'
        ),
        'factories' => array(
            'Rcm\Controller\IndexController'
                => 'Rcm\Factory\IndexControllerFactory',
        ),
    ),
    'view_helpers' => array(
        'factories' => array(
            'rcmContainer'
                => 'Rcm\Factory\ContainerViewHelperFactory',
            'rcmTextEdit' => 'Rcm\Factory\TextEditFactory',
            'rcmRichEdit' => 'Rcm\Factory\RichEditFactory',
        ),
        'invokables' => array(
            'rcmOutOfDateBrowserWarning'
                => 'Rcm\View\Helper\OutOfDateBrowserWarning',
            'urlToPage'
                => 'Rcm\View\Helper\UrlToPage',
            'revisionHelper'
                => 'Rcm\View\Helper\RevisionHelper',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'shouldShowRevisions'
                => 'Rcm\Factory\ShouldShowRevisionsPluginFactory',
            'rcmIsAllowed' =>
                'Rcm\Factory\RcmIsAllowedFactory',
            'rcmIsSiteAdmin' =>
                'Rcm\Factory\IsSiteAdminPluginFactory',
            'rcmIsPageAllowed' =>
                '\Rcm\Factory\RcmIsPageAllowedPluginFactory',
        ),
        'invokables' => array(
            'redirectToPage'
                => 'Rcm\Controller\Plugin\RedirectToPage',
            'urlToPage'
                => 'Rcm\Controller\Plugin\UrlToPage',
        ),
    ),
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm/' => __DIR__ . '/../public/',
            ],
        ],
    ],
);
