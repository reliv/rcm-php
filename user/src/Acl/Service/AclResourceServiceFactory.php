<?php

namespace RcmUser\Acl\Service;

use Interop\Container\ContainerInterface;
use RcmUser\Acl\Provider\ResourceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AclResourceServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AclResourceServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return AclResourceService
     */
    public function __invoke($serviceLocator)
    {
        /** @var ResourceProviderInterface $resourceProvider */
        $resourceProvider = $serviceLocator->get(
            \RcmUser\Acl\Provider\ResourceProvider::class
        );

        /** @var \RcmUser\Acl\Builder\AclResourceStackBuilder $aclResourceStackBuilder */
        $aclResourceStackBuilder = $serviceLocator->get(
            \RcmUser\Acl\Builder\AclResourceStackBuilder::class
        );

        $service = new AclResourceService(
            $resourceProvider,
            $aclResourceStackBuilder
        );

        return $service;
    }
}
