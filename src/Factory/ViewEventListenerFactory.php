<?php

namespace Rcm\Factory;

use Rcm\EventListener\ViewEventListener;
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
class ViewEventListenerFactory
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return ViewEventListener
     */
    public function __invoke($serviceLocator)
    {
        /** @var \Rcm\Service\ResponseHandler $responseHandler */
        $responseHandler = $serviceLocator->get(\Rcm\Service\ResponseHandler::class);

        return new ViewEventListener($responseHandler);
    }
}
