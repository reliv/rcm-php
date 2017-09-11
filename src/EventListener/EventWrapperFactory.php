<?php

namespace Rcm\EventListener;

use Rcm\EventListener\EventWrapper;
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
class EventWrapperFactory
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return EventWrapper
     */
    public function __invoke($serviceLocator)
    {
        return new EventWrapper(
            $serviceLocator
        );
    }
}
