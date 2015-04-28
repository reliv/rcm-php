<?php
/**
 * Get composer autoloader
 */
$dots = '';
for ($i = 1; $i < 20; $i++) {
    $dots .= '../';
    if (file_exists(__DIR__ . '/' . $dots . 'vendor/autoload.php')) {
        //Get the composer autoloader from vendor folder as a standalone module
        $autoload = __DIR__ . '/' . $dots . 'vendor/autoload.php';
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