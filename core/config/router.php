<?php
/**
 * router.php
 */
return [
    'routes' => [
        'api-admin-instance-configs' => [
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => [
                'route' => '/api/admin/instance-configs/:pluginType/:id',
                'defaults' => [
                    'controller' => \Rcm\Controller\InstanceConfigApiController::class,
                ],
            ],
        ],
        /* @deprecated */
        'contentManager' => [
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => [
                'route' => '/rcm[/:page][/:revision]',
                'defaults' => [
                    'controller' => \Rcm\Controller\IndexController::class,
                    'action' => 'index',
                ],
            ],
        ],
        /* CmsRoute Example*/
        //'rcmCmsPageRevisionRoute' => [
        //    'type' => \Rcm\Route\Cms::class,
        //    'options' => [
        //        'route' => '/rcm[/:page][/:revision]',
        //        // optional: Defaults to PageTypes::NORMAL if left blank
        //        'type' => PageTypes::NORMAL,
        //        'defaults' => [
        //            // optional: Defaults to Rcm\Controller\CmsController if blank
        //            'controller' => 'Rcm\Controller\CmsController',
        //            // optional: Defaults to indexAction if blank
        //            'action' => 'index',
        //        ],
        //    ],
        //],
        'contentManagerWithPageType' => [
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => [
                'route' => '/rcm/:pageType/:page[/:revision]',
                'constraints' => [
                    'pageType' => '[a-z]',
                ],
                'defaults' => [
                    'controller' => \Rcm\Controller\IndexController::class,
                    'action' => 'index',
                ],
            ],
        ],
        'blog' => [
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => [
                'route' => '/blog[/:page]',
                'defaults' => [
                    'controller' => \Rcm\Controller\IndexController::class,
                    'action' => 'index',
                ],
            ],
        ],
        'contentManagerNewInstanceAjax' => [
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => [
                'route' => '/rcm-admin-get-instance/:pluginType/:instanceId',
                'defaults' => [
                    'controller' => \Rcm\Controller\NewPluginInstanceApiController::class,
                    'action' => 'getNewInstance',
                ],
            ],
        ],
        '/rcm/core/rpc/render-plugin-instance-preview' => [
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => [
                'route' => '/rcm/core/rpc/render-plugin-instance-preview',
                'defaults' => [
                    'controller' => \Rcm\Controller\RenderPluginInstancePreviewApiController::class,
                    'action' => 'index',
                ],
            ],
        ],
        'rcm-page-title-search' => [
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => [
                'route' => '/rcm-page-search/title[/:query]',
                'defaults' => [
                    'controller' => \Rcm\Controller\PageSearchApiController::class,
                    'action' => 'siteTitleSearch',
                ],
            ],
        ],
        'rcm-page-search' => [
            'type' => 'Zend\Mvc\Router\Http\Segment',
            'options' => [
                'route' => '/rcm-page-search[/:language]',
                'defaults' => [
                    'controller' => \Rcm\Controller\PageSearchApiController::class,
                    'action' => 'allSitePages',
                ],
            ],
        ],
        'Rcm\Api\Page\Check' => [
            'type' => 'Segment',
            'options' => [
                'route' => '/rcm/page/check[/:pageType]/:pageId',
                'defaults' => [
                    'controller' => \Rcm\Controller\PageCheckController::class,
                ],
            ],
        ],
        'Rcm\Cache\Flush' => [
            'type' => 'Zend\Mvc\Router\Http\Literal',
            'options' => [
                'route' => '/rcm/cache/flush',
                'defaults' => [
                    'controller' => \Rcm\Controller\CacheController::class,
                    'action' => 'flush',
                ],
            ],
        ],
    ],
];
