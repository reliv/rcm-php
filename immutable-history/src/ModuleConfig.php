<?php

namespace Rcm\ImmutableHistory;

use Rcm\ImmutableHistory\Page\ImmutablePageVersion;
use Rcm\ImmutableHistory\Page\ImmutablePageVersionEntity;
use Rcm\ImmutableHistory\Page\PageContentFactory;
use Rcm\ImmutableHistory\Page\RcmPluginWrappersToRcmImmutablePluginInstances;

class ModuleConfig
{
    public function __invoke()
    {
        return [
//            'depedencies' => [ //@TODO use this expressive key instead
            'service_manager' => [
                'config_factories' => [
                    'Rcm\ImmutableHistory\PageVersionRepo' => [
                        'class' => VersionRepository::class,
                        'arguments' => [
                            ['literal' => ImmutablePageVersionEntity::class],
                            \Doctrine\ORM\EntityManager::class
                        ]
                    ],
                    PageContentFactory::class => []
                ]
            ],
            'doctrine' => [
                'driver' => [
                    'Rcm\ImmutableHistory\Page' => [
                        'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                        'cache' => 'array',
                        'paths' => [
                            __DIR__ . '/Page'
                        ]
                    ],
                    'orm_default' => [
                        'drivers' => [
                            'Rcm\ImmutableHistory\Page' => 'Rcm\ImmutableHistory\Page'
                        ]
                    ]
                ],
            ]
        ];
    }
}
