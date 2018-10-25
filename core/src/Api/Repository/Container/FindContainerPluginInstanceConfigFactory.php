<?php

namespace Rcm\Api\Repository\Container;

use Interop\Container\ContainerInterface;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindContainerPluginInstanceConfigFactory
{
    /**
     * @param ContainerInterface $serviceContainer
     *
     * @return FindContainerPluginInstanceConfig
     */
    public function __invoke($serviceContainer)
    {
        return new FindContainerPluginInstanceConfig(
            $serviceContainer->get(FindContainerPluginInstance::class)
        );
    }
}
