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
        'display_not_found_reason' => false,
        'display_exceptions' => false,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml'
        ),
        'strategies' => array(
            'ViewJsonStrategy',
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

            'rcm-api-states' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/rcm-api/counties/:country/states',
                    'defaults' => array(
                        'controller' => 'rcmStateApiController',
                        'action' => 'listStates',
                    ),
                ),
            ),

            'rcm-plugin-admin-proxy' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' =>
                        '/rcm-plugin-admin-proxy/:pluginName/:instanceId/:pluginActionName',
                    'defaults' => array(
                        'controller' => 'rcmPluginProxyController',
                        'action' => 'adminProxy',
                    )
                ),
            ),

            'plugin-ajax-proxy' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' =>
                        '/plugin-ajax-proxy/:pluginName/:instanceId/:pluginActionName',
                    'defaults' => array(
                        'controller' => 'rcmPluginProxyController',
                        'action' => 'ajaxProxy',
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

    'rcmLogger' => array(
        'writer' => 'rcmLogWriterStub'
    ),

    'rcmLogWriter' => array(
        'logPath' => '',
    )

);