<?php
return [
    'abstract_factories' => [
        \Rcm\Factory\AbstractPluginControllerFactory::class
    ],
    'config_factories' => [
//        \Rcm\Controller\CacheController::class => [
//            'class' => \Rcm\Controller\CacheController::class,
//            'arguments' => ['ServiceManager'],
//        ],
        \Rcm\Controller\InstanceConfigApiController::class => [
            'class' => \Rcm\Controller\InstanceConfigApiController::class,
            'arguments' => ['ServiceManager'],
        ],
        \Rcm\Controller\NewPluginInstanceApiController::class => [
            'class' => \Rcm\Controller\NewPluginInstanceApiController::class,
            'arguments' => ['ServiceManager'],
        ],
        \Rcm\Controller\RenderPluginInstancePreviewApiController::class => [
            'class' => \Rcm\Controller\RenderPluginInstancePreviewApiController::class,
            'arguments' => ['Rcm\Service\PluginManager'],
        ],
        \Rcm\Controller\PageCheckController::class => [
            'class' => \Rcm\Controller\PageCheckController::class,
            'arguments' => ['ServiceManager'],
        ],
        \Rcm\Controller\PageSearchApiController::class => [
            'class' => \Rcm\Controller\PageSearchApiController::class,
            'arguments' => ['ServiceManager'],
        ],
    ],
    'factories' => [
        Rcm\Controller\IndexController::class => Rcm\Factory\IndexControllerFactory::class,
//        Rcm\Controller\CmsController::class => Rcm\Factory\CmsControllerFactory::class,
    ],
];
