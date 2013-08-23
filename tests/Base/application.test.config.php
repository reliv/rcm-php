<?php

return array(
    'modules' =>  array(

        //Rcm Dependencies
        'DoctrineModule',
        'DoctrineORMModule',

        //RCM core and plugins
        'Rcm',
        'RcmSimpleConfigStorage',
        'RcmHtmlArea',
        'RcmNavigation',
        'RcmCallToActionBox',
        'RcmPortalAnnouncementBox',
        'RcmImageWithThumbnails',
        'RcmCallToActionBox',
        'RcmRotatingImage',
        'RcmEventCalenderCore',
        'RcmEventListDisplay',
        'RcmPeopleSlider',
        'RcmGoogleSearchBox',
        'RcmGoogleSearchResults',
        'RcmTabs',

        'RcmLogin',
        'RcmSocialButtons',
        'RcmRssFeed',

        //OS Theme
        'RcmGeneric',

        //MUST BE AT BOTTOM OF DEFINITION
        'ElFinder',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            __DIR__.'/../config/test.php',
        ),
        'module_paths' => array(
            './vendor',
            './vendor/reliv',
            './vendor/reliv/RcmPlugins',
            './vendor/reliv/Rcm/themes',
            './module',//Must be last for overriding open source versions of plugins
        ),
    ),
);