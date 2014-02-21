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
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
// ZF2 project root folder that contains public, module, vendor, etc.
$projectRootDir = realpath(__DIR__ . '/../../../..');

// Path where the symlinks will be written
$publicLinkHome = $projectRootDir . '/public/modules/';

if (!file_exists($publicLinkHome)) {

    //Create the public/modules directory if it does not already exist
    echo "Creating directory $publicLinkHome\n";
    mkdir($publicLinkHome);

    //Add a .gitignore file to the modules directory
    $gitIgnorePath = $publicLinkHome . '.gitignore';
    echo "Creating file $gitIgnorePath\n";
    file_put_contents($gitIgnorePath, '*');
}

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

    // Module's public folder
    $modulePublicPath = $modulePath . '/public';

    // Module's CamelCase name
    $moduleName = basename($modulePath);

    // Check if this module has a public folder
    if (is_dir($modulePublicPath)) {

        // Path where the symlink will be written. lower-case-hyphens are
        // used because this path will end up in URLs
        $symlinkPath = $publicLinkHome . camelToHyphens($moduleName);

        // Remove link if already there. this is useful for when things are
        //renamed but their old links remain
        if (is_link($symlinkPath)) {
            unlink($symlinkPath);
        }

        // Create the symlink
        /*echo "Symlink\n    From: $symlinkPath\n"
            . "    To: $modulePublicPath\n";*/
        symlink($modulePublicPath, $symlinkPath);
    }

}

/*
 * Converts camelCase to lower-case-hyphens
 *
 * @param string $value the value to convert
 *
 * @return string
 */
function camelToHyphens($value)
{
    return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $value));
}

/*
 * Converts lower-case-hyphens to camelCase
 *
 * @param string $value the value to convert
 *
 * @return string
 */
function hyphensToCamel($value)
{
    return preg_replace("/\-(.)/e", "strtoupper('\\1')", $value);
}