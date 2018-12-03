./vendor/bin/phpunit
./vendor/bin/parallel-lint --exclude ./vendor --exclude ./test -j 256 .
./vendor/bin/phpcs --standard=PSR2 --ignore=vendor,test,config,data,autoload_classmap.php --extensions=php ./
