<?php
/**
 * asset_manager.php
 */
return [
    'resolver_configs' => [
        'aliases' => [
            'bower_components/' => $bowerComponentsDir,
            // Legacy support because bower_components used to be named "vendor"
            'vendor/' => $bowerComponentsDir
        ],
        'collections' => [
            /**
             * Core JS and css
             * (core features)
             */
            'modules/rcm/rcm.js' => [],
            'modules/rcm/rcm.css' => [],
            /**
             * Extended JS and css
             * (features for modules and lower level services)
             */
            'modules/rcm/modules.js' => [],
            'modules/rcm/modules.css' => [],
        ],
    ],
];
