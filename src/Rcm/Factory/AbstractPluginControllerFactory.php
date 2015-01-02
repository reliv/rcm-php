<?php
/**
 * AbstractPluginControllerFactory.php
 *
 * This class allows plugins to not have to have their own controller or
 * controller factory if they contain no custom php code.
 *
 * PHP version 5
 *
 * @category  Reliv
 * @package   Rcm\Factory
 * @author    Rod Mcnew <rmcnew@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

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
 * @copyright 2014 Reliv International
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
        $config = $serviceLocator->get('config');
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
            $serviceLocator->get('config'),
            $requestedName
        );
    }
}