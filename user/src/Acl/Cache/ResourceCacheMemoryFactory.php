<?php

namespace RcmUser\Acl\Cache;

use Interop\Container\ContainerInterface;
use Zend\Cache\Storage\Adapter\Memory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResourceCacheMemoryFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ResourceCacheMemoryFactory
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
        $resourceStorage = new Memory();
        $resourceStorage->setOptions(['memoryLimit' => 0]);

        $providerIndexStorage = new Memory();
        $providerIndexStorage->setOptions(['memoryLimit' => 0]);

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
