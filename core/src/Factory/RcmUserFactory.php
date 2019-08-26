<?php

namespace Rcm\Factory;

use Rcm\Service\RcmUser;
use Zend\ServiceManager\ServiceLocatorInterface;

class RcmUserFactory
{
    /**
     * createService
     *
     * @param ServiceLocatorInterface $serviceLocator mgr
     *
     * @return mixed
     */
    public function __invoke($serviceLocator)
    {
        /** @var \RcmUser\Service\RcmUserService $rcmUserService */
        $rcmUserService = $serviceLocator->get(
            \RcmUser\Service\RcmUserService::class
        );

        $service = new RcmUser($rcmUserService);

        return $service;
    }
}
