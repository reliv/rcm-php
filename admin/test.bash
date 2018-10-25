#!/usr/bin/env bash

echo ""
echo "[test.bash] Running Composer install..."
composer self-update
composer --version
composer install --prefer-dist

echo ""
echo "[test.bash] Running PHP unit tests..."
php ./vendor/bin/phpunit

echo ""
echo "[test.bash] Running PHP Code Sniffer..."
php ./vendor/bin/phpcs --standard=PSR2 --ignore=vendor,test,config,data,autoload_classmap.php --extensions=php ./
