<?php

namespace RcmUser\Acl\Builder;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class ResourceProviderBuilderFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class ResourceProviderBuilderFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     *
     * @return ResourceProviderBuilder
     */
    public function __invoke($serviceLocator)
    {
        $service = new ResourceProviderBuilder(
            $serviceLocator
        );

        return $service;
    }
}
