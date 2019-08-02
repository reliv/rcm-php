<?php

namespace RcmUser\Acl\Builder;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AclResourceBuilderFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class AclResourceBuilderFactory
{
    /**
     * createService
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator serviceLocator
     *
     * @return AclResourceBuilder
     */
    public function __invoke($serviceLocator)
    {
        /** @var \RcmUser\Acl\Entity\RootAclResource $rootResource */
        $rootResource = $serviceLocator->get(
            \RcmUser\Acl\Entity\RootAclResource::class
        );

        $service = new AclResourceBuilder(
            $rootResource
        );

        return $service;
    }
}
