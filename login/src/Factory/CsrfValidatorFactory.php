<?php

namespace RcmLogin\Factory;

use Rcm\Service\SessionManager;
use RcmLogin\Csrf\CsrfValidator;
use Zend\Session\Container;

class CsrfValidatorFactory
{
    public function __invoke($serviceContainer)
    {
        $sessionContainer = new Container('RcmLogin\Csrf', $serviceContainer->get(SessionManager::class));

        return new CsrfValidator([
            'session' => $sessionContainer,
            'timeout' => $serviceContainer->get('config')['rcmPlugin']['RcmLogin']['csrfTimeoutSeconds']
        ]);
    }
}
