<?php
return [
    'router' => [
        'routes' => [
            'list-html-includes-scripts' => [
                'options' => [
                    'route' => 'list-html-includes-scripts',
                    'defaults' => [
                        'controller' => 'Rcm\ConsoleController\ListHtmlIncludesController',
                        'action' => 'listScripts'
                    ]
                ]
            ],
            'list-html-includes-stylesheets' => [
                'options' => [
                    'route' => 'list-html-includes-stylesheets',
                    'defaults' => [
                        'controller' => 'Rcm\ConsoleController\ListHtmlIncludesController',
                        'action' => 'listStylesheets'
                    ]
                ]
            ]
        ]
    ]
];
