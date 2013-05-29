<?php
/**
 * ZF2 Module Public Asset Installer
 *
 * This PHP CLI script allows ZF2 modules to have routable public asset
 * folders. Urls like /vendor/RodsZf2Module/css/style.css will map to
 * /vendor/rod/RodsZf2Module/public/css/style.css This is accomplished by
 * creating symlinks. This script must run from your ZF2 project root folder.
 * This script is similar to the Symfony 2 command
 * "php app/console assets:install"..
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://ci.reliv.com/confluence
 */
// ZF2 project root folder that contains public, module, vendor, etc.
$projectRootDir = realpath(__DIR__ . '/../../../..');

// Find all Module.php files in order to locate all ZF2 modules.
$modulePhpFiles = new RegexIterator(
    new RecursiveIteratorIterator(
        New RecursiveDirectoryIterator($projectRootDir)
    ),
    '/^.+Module.php$/i',
    RecursiveRegexIterator::GET_MATCH
);

// Add a symlink to the main public directory for each ZF2 module that has a
// public directory
foreach ($modulePhpFiles as $modulePhpFilePath) {

    // Module's main folder
    $modulePath = realpath(dirname($modulePhpFilePath[0]));

    echo exec('cd '.$modulePath.';/usr/local/zend/share/ZendFramework2/bin/classmap_generator.php')."\n";

}