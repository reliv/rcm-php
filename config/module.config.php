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
                        'controller' => 'Rcm\Controller\IndexController',
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
                        'controller' => 'Rcm\Controller\IndexController',
                        'action' => 'index',
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
            'doctrine.cache.doctrine_cache'
                => 'Rcm\Factory\DoctrineCacheFactory',

            'Rcm\EventListener\RouteListener'
                => 'Rcm\Factory\RouteListenerFactory',

            'Rcm\EventListener\DispatchListener'
                => 'Rcm\Factory\DispatchListenerFactory',

            'Rcm\Service\ContainerManager'
                => 'Rcm\Factory\ContainerManagerFactory',

            'Rcm\Service\PluginManager'
                => 'Rcm\Factory\PluginManagerFactory',

            'Rcm\Service\LayoutManager'
                => 'Rcm\Factory\LayoutManagerFactory',

            'Rcm\Service\DomainManager'
                => 'Rcm\Factory\DomainManagerFactory',

            'Rcm\Service\SiteManager'
                => 'Rcm\Factory\SiteManagerFactory',

            'Rcm\Service\PageManager'
                => 'Rcm\Factory\PageManagerFactory',

            'Rcm\Service\Cache'
                => 'Rcm\Factory\CacheFactory',

            'Rcm\Service\SessionMgr'
                => 'Rcm\Factory\SessionManagerFactory',
        ),
    ),

    'controllers' => array(
        'factories' => array (
            'Rcm\Controller\IndexController'
                => 'Rcm\Factory\IndexControllerFactory',
        ),
    ),

    'view_helpers' => array(
        'factories' => array(
            'rcmContainer'
                => 'Rcm\Factory\ContainerViewHelperFactory',
        ),
        'invokables' => array(
            'rcmOutOfDateBrowserWarning'
                => 'Rcm\View\Helper\OutOfDateBrowserWarning',
        ),
    )

);