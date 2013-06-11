<?php
/**
 * ZF2 All Module Classmap Generator
 *
 * Searches for all ZF2 modules in a folder and then runs the ZF2 classmap generator on each module
 *
 * @author    Rod McNew
 * @license   License.txt New BSD License
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