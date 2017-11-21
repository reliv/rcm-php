<?php

namespace Rcm\Api\Repository\Container;

use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindContainerPluginInstanceFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindContainerPluginInstance
     */
    public function __invoke($serviceContainer)
    {
        return new FindContainerPluginInstance(
            $serviceContainer->get(FindContainer::class)
        );
    }
}
