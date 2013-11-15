<?php

return array(
    'modules' =>  array(

        //Rcm Dependencies
        -4 => 'DoctrineModule',
        -3 => 'DoctrineORMModule',

        //RCM core and plugins
        -2 => 'Rcm',
        -1 => 'RcmDoctrineJsonPluginStorage',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            -1 => __DIR__.'/../../config/test.php',
        ),
        'module_paths' => array(
            -3 => './vendor',
            -2 => './vendor/reliv',
            -1 => './vendor/reliv/RcmPlugins',
        ),
    ),
);