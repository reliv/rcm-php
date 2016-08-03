<?php
/**
 * doctrine.php
 */
return [
    'driver' => [
        'relivContentManager' => [
            'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
            'cache' => 'array',
            'paths' => [
                __DIR__ . '/../src/Entity'
            ]
        ],
        'orm_default' => [
            'drivers' => [
                'Rcm' => 'relivContentManager'
            ]
        ]
    ],
    'configuration' => [
        'orm_default' => [
            'metadata_cache' => 'doctrine_cache',
            'query_cache' => 'doctrine_cache',
            'result_cache' => 'doctrine_cache',
        ]
    ],
];
