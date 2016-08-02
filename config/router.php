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
                    'controller' => 'Rcm\Controller\InstanceConfigApiController',
                ]
            ],
        ],
        /* @deprecated */
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
        /* CmsRoute Example*/
        //'rcmCmsPageRevisionRoute' => [
        //    'type' => 'Rcm\Route\Cms',
        //    'options' => [
        //        'route' => '/rcm[/:page][/:revision]',
        //        // optional: Defaults to 'n' if left blank
        //        'type' => 'n',
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
                    'controller' => 'Rcm\Controller\IndexController',
                    'action' => 'index',
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
];
