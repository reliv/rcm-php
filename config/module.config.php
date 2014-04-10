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
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
return array(

    'Rcm' => array(
        'successfulLoginUrl' => '/'
    ),

    'asset_manager' => array(
        'caching' => array(
            'default' => array(
                'cache'     => 'Filesystem',
                'options' => array(
                    'dir' => __DIR__.'/../../../../public', // path/to/cache
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'router' => array(
        'routes' => array(

            'contentManager' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm[/:page][/:revision]',
                    'defaults' => array(
                        'controller' => 'rcmIndexController',
                        'action' => 'index',
                    )
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
                        'controller' => 'rcmIndexController',
                        'action' => 'index',
                    )
                ),
            ),

            'blog' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/blog[/:page]',
                    'defaults' => array(
                        'controller' => 'rcmIndexController',
                        'action' => 'index',
                    )
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
        'options' => array(
            //'namespace' => 'RcmCache'
        )
    ),

    'service_manager' => array(
        'factories' => array (
            'doctrine.cache.doctrine_cache' => 'Rcm\Factory\DoctrineCacheFactory',
            'rcmRouteListener'              => 'Rcm\Factory\RouteListenerFactory',
            'rcmDispatchListener'           => 'Rcm\Factory\DispatchListenerFactory',
            'rcmContainerManager'           => 'Rcm\Factory\ContainerManagerFactory',
            'rcmPluginManager'              => 'Rcm\Factory\PluginManagerFactory',
            'rcmLayoutManager'              => 'Rcm\Factory\LayoutManagerFactory',
            'rcmDomainManager'              => 'Rcm\Factory\DomainManagerFactory',
            'rcmSiteManager'                => 'Rcm\Factory\SiteManagerFactory',
            'rcmPageManager'                => 'Rcm\Factory\PageManagerFactory',
            'rcmCache'                      => 'Rcm\Factory\CacheFactory',
            'rcmSessionMgr'                 => 'Rcm\Factory\SessionManagerFactory',
        ),

        'aliases' => array(
            'em'                            => 'doctrineormentitymanager',
        )
    ),

    'controllers' => array(
        'factories' => array (
            'rcmIndexController'            => 'Rcm\Factory\IndexControllerFactory',
        ),
    ),

    'view_helpers' => array(
        'factories' => array(
            'rcmContainer'                  => 'Rcm\Factory\ContainerViewHelperFactory',
        ),
        'invokables' => array(
            'rcmOutOfDateBrowserWarning'    => 'Rcm\View\Helper\OutOfDateBrowserWarning',
        ),
    )

);