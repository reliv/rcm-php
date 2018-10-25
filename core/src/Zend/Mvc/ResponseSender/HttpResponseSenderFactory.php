<?php

namespace Rcm\Zend\Mvc\ResponseSender;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class HttpResponseSenderFactory
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Manager
     *
     * @return HttpResponseSender
     */
    public function __invoke($serviceLocator)
    {
        return new HttpResponseSender();
    }
}
