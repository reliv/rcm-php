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
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */

return array(

    'rcmPlugin' => array(
        'RcmMockPlugin' => array(
            'type' => 'Common',
            'display' => 'Mock Object Display Name',
            'tooltip' => 'This plugin does absolutely nothing.  Do not use!',
            'icon' => ''
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'controllers' => array(
        'factories' => array(
            'RcmMockPlugin' => 'RcmMockPlugin\Factory\RcmMockPluginFactory'
        )
    )

);