<?php
/**
 * autoload.php
 *
 * LongDescHere
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmLogin
 * @author    Inna Davis <author@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

$autoload = '';

/**
 * Look backwards till wer find the vendor folder. On jenkins it lives in a
 * different path which makes this needed
 */
$dots = '';
for ($i = 1; $i < 20; $i++) {
    $dots .= '../';
    if (file_exists(__DIR__ . 'autoload.php/' . $dots . 'vendor/autoload.php')) {
        //Get the composer autoloader from vendor folder as a standalone module
        $autoload = __DIR__ . 'autoload.php/' . $dots . 'vendor/autoload.php';
        break;
    }
}

if (empty($autoload)) {
    trigger_error(
        'Please make sure to run composer install before running unit tests',
        E_USER_ERROR
    );
}

require_once $autoload;
