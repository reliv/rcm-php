<?php

namespace RcmLogin\Factory;

use RcmLogin\EventListener\Login;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class LoginEventListenerFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   RcmLogin\Factory
 * @copyright 2015 Reliv International
 * @license   License.txt
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class LoginEventListenerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $eventListener = new Login(
            $serviceLocator->get('RcmLogin\Filter\RedirectFilter'),
            ['redirect', 'redirect-from']
        );

        return $eventListener;
    }
}
