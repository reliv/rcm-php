<?php

return array(
    'modules' => array(

        //Rcm Dependencies
        -4 => 'DoctrineModule',
        -3 => 'DoctrineORMModule',

        //RCM core and plugins
        -2 => 'Rcm',
        -1 => 'RcmInstanceConfig',
    ),
    'module_listener_options' => array(
        'config_glob_paths' => array(
            -1 => __DIR__ . '/test.config.php',
        ),
        'module_paths' => array(

            -4 => './vendor',
            -3 => './vendor/reliv',
            -2 => './vendor/reliv/RcmPlugins',
            -1 => './vendor/reliv/RcmUser',
        ),
    ),
);