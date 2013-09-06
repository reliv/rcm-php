<?php

require __DIR__.'/Base/RcmBootstrap.php';

use \Rcm\Tests\Base\RcmBootstrap;

class Bootstrap extends RcmBootstrap
{

}
/** Array is zend special application config */
Bootstrap::init(include 'application.test.config.php');