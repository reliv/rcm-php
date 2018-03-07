<?php

namespace Rcm\Factory;

use Rcm\Plugin\BaseController;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * AbstractPluginControllerFactory
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2018 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: <package_version>
 * @link      https://github.com/reliv
 */
class AbstractPluginControllerFactory implements AbstractFactoryInterface
{
    /**
     * Determine if we can create a service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return bool
     */
    public function canCreateServiceWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName
    ) {
        $config = $serviceLocator->getServiceLocator()->get('Config');

        return isset($config['rcmPlugin'][$requestedName]);
    }

    /**
     * Create service with name
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param                         $name
     * @param                         $requestedName
     *
     * @return mixed
     */
    public function createServiceWithName(
        ServiceLocatorInterface $serviceLocator,
        $name,
        $requestedName
    ) {
        return new BaseController(
            $serviceLocator->getServiceLocator()->get('Config'),
            $requestedName,
            $serviceLocator
        );
    }
}
