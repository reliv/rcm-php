<?php

namespace RcmUser\Acl\Builder;

use Interop\Container\ContainerInterface;
use RcmUser\Acl\Provider\ResourceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AclResourceStackBuilderFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AclResourceStackBuilderFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return AclResourceStackBuilder
     */
    public function __invoke($serviceLocator)
    {
        /** @var ResourceProviderInterface $resourceProvider */
        $resourceProvider = $serviceLocator->get(
            \RcmUser\Acl\Provider\ResourceProvider::class
        );

        $service = new AclResourceStackBuilder(
            $resourceProvider
        );

        return $service;
    }
}
