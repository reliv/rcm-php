<?php

namespace RcmUser\Acl\Entity;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RootAclResourceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class RootAclResourceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return RootAclResource
     */
    public function __invoke($serviceLocator)
    {
        $service = new RootAclResource();

        return $service;
    }
}
