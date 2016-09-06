<?php
return [
    'router' => [
        'routes' => [
            'list-js-includes' => [
                'options' => [
                    'route' => 'list-js-includes',
                    'defaults' => [
                        'controller' => 'Rcm\ConsoleController\ListHtmlIncludesController',
                        'action' => 'listJs'
                    ]
                ]
            ],
            'list-css-includes' => [
                'options' => [
                    'route' => 'list-css-includes',
                    'defaults' => [
                        'controller' => 'Rcm\ConsoleController\ListHtmlIncludesController',
                        'action' => 'listCss'
                    ]
                ]
            ]
        ]
    ]
];
