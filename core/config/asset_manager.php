<?php
/**
 * asset_manager.php
 */
$bowerComponentsDir = __DIR__ . '/../../../../../public/bower_components/';
return [
    'resolver_configs' => [
        'aliases' => [
            'modules/rcm/' => __DIR__ . '/../public/',
            'bower_components/' => $bowerComponentsDir,
            // Legacy support because bower_components used to be named "vendor"
            'vendor/' => $bowerComponentsDir
        ],
        'collections' => [
            /**
             * Core JS and css
             * (core features)
             */
            'modules/rcm/rcm.js' => [
                'modules/rcm/place-holder.js'
            ],
            'modules/rcm/rcm.css' => [
                'modules/rcm/place-holder.css'
            ],
            /**
             * Extended JS and css
             * (features for modules and lower level services)
             */
            'modules/rcm/modules.js' => [
                'modules/rcm/place-holder.js'
            ],
            'modules/rcm/modules.css' => [
                'modules/rcm/place-holder.css'
            ],
        ],
    ],
];
