<?php

namespace Rcm\EventListener;

use Rcm\EventListener\DispatchListener;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the DispatchListener
 *
 * Factory for the Dispatch Listener.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class DispatchListenerFactory
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return DispatchListener
     */
    public function __invoke($serviceLocator)
    {
        return new DispatchListener(
            $serviceLocator
        );
    }
}
