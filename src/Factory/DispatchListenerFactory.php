<?php
/**
 * Service Factory for the Dispatch Listener
 *
 * This file contains the factory needed to generate a Dispatch Listener.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace RcmAdmin\Factory;

use RcmAdmin\EventListener\DispatchListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Dispatch Listener
 *
 * Factory for the Dispatch Listener
 *
 * @category  Reliv
 * @package   RcmAdmin
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class DispatchListenerFactory implements FactoryInterface
{
    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return DispatchListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DispatchListener($serviceLocator);
    }
}
