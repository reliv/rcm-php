<?php

namespace Rcm\ImmutableHistory;

use Rcm\ImmutableHistory\Entity\ImmutablePageVersion;

class ModuleConfig
{
    public function __invoke()
    {
        return [
//            'depedencies' => [
            'service_manager' => [
                'config_factories' => [
                    'Rcm\ImmutableHistory\PageVersionRepo' => [
                        'class' => VersionRepository::class,
                        'arguments' => [
                            ['literal' => ImmutablePageVersion::class],
                            \Doctrine\ORM\EntityManager::class
                        ]
                    ]
                ]
            ],
            'doctrine' => [
                'driver' => [
                    'Rcm\ImmutableHistory' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/Entity'
                        ]
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'Rcm\ImmutableHistory' => 'Rcm\ImmutableHistory'
                        ]
                    ]
                ],
            ]
        ];
    }
}
