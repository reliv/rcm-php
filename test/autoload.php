<?php

$autoload = '';


if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    //Get the composer autoloader from vendor folder as a standalone module
    $autoload = __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    //Get the composer autoloader from vendor folder as a standalone module
    $autoload = __DIR__ . '/../../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../../../autoload.php')) {
    //Get the composer autoloader when you're in the vendor folder
    $autoload = __DIR__ . '/../../../../autoload.php';
}

if (empty($autoload)) {
    trigger_error(
        'Please make sure to run composer install before running unit tests',
        E_USER_ERROR
    );
}

require_once $autoload;


