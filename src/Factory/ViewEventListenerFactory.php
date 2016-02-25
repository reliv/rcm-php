<?php

namespace Rcm\Factory;

use Rcm\EventListener\EventFinishListener;
use Rcm\EventListener\ViewEventListener;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Event Finish Listener
 *
 * Factory for the Event Finish Listener.
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
class ViewEventListenerFactory implements FactoryInterface
{

    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return EventFinishListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\ResponseHandler $responseHandler */
        $responseHandler = $serviceLocator->get('Rcm\Service\ResponseHandler');

        return new ViewEventListener($responseHandler);
    }
}
