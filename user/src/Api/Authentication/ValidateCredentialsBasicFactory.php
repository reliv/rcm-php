<?php

namespace RcmUser\Api\Authentication;

use Interop\Container\ContainerInterface;
use RcmUser\Authentication\Service\UserAuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class ValidateCredentialsBasicFactory
{
    /**
     * @param ContainerInterface|ServiceLocatorInterface $serviceContainer
     *
     * @return ValidateCredentialsBasic
     */
    public function __invoke($serviceContainer)
    {
        return new ValidateCredentialsBasic(
            $serviceContainer->get(UserAuthenticationService::class)
        );
    }
}
