<?php

/**
 * ZF2 Plugin Config file
 *
 * This file contains all the configuration for the Module as defined by ZF2.
 * See the docs for ZF2 for more information.
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   RcmPlugins\Navigation
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

return array(

    'Rcm' => array(
        'themes' => array(
            'RcmGeneric' => array(
                'screenShot' => '/modules/reliv-guest-site/images/GuestSiteTheme.png',
                'display' => 'Default Site',
                'layouts' => array(
                    'default' => array(
                        'display' => 'Generic Page',
                        'file' => 'rcm-generic-home-page.phtml',
                        'screenShot' => '/modules/reliv-guest-site/images/GuestLayoutPageScreenshot.png',
                        'hidden' => true,
                    ),
                    'homePage' => array(
                        'display' => 'Home Page',
                        'file' => 'rcm-generic-home-page.phtml',
                        'screenShot' => '/modules/reliv-guest-site/images/GuestLayoutPageScreenshot.png',
                        'hidden' => true,
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

);