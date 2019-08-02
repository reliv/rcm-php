<?php

namespace RcmUser\Acl\Cache;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResourceCacheArrayFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ResourceCacheArrayFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return ResourceCache
     */
    public function __invoke($serviceLocator)
    {
        $resourceStorage = new ArrayStorage();

        $providerIndexStorage = new ArrayStorage();

        /** @var \RcmUser\Acl\Builder\AclResourceBuilder $resourceBuilder */
        $resourceBuilder = $serviceLocator->get(
            \RcmUser\Acl\Builder\AclResourceBuilder::class
        );

        $service = new ResourceCache(
            $resourceStorage,
            $providerIndexStorage,
            $resourceBuilder
        );

        return $service;
    }
}
