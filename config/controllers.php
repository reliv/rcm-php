<?php
return [
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
        'Rcm\Controller\RenderPluginInstancePreviewApiController' => [
            'class' => 'Rcm\Controller\RenderPluginInstancePreviewApiController',
            'arguments' => ['Rcm\Service\PluginManager'],
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
];
