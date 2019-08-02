<?php

namespace RcmUser\Acl\Provider;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RootResourceProviderFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RootResourceProviderFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return RootResourceProvider
     */
    public function __invoke($serviceLocator)
    {
        /** @var \RcmUser\Acl\Entity\RootAclResource $rootResource */
        $rootResource = $serviceLocator->get(
            \RcmUser\Acl\Entity\RootAclResource::class
        );

        $service = new RootResourceProvider(
            $rootResource
        );

        return $service;
    }
}
