<?php

namespace Rcm\Factory;

use Rcm\EventListener\EventWrapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Event Wrapper
 *
 * Factory for the Event Wrapper
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
class EventWrapperFactory implements FactoryInterface
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return EventWrapper
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new EventWrapper(
            $serviceLocator
        );
    }
}
