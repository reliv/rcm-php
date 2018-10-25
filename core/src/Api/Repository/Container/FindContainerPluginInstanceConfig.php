<?php

namespace Rcm\Api\Repository\Container;

use Rcm\Entity\PluginInstance;
use Rcm\Exception\PluginInstanceNotFoundException;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindContainerPluginInstanceConfig
{
    protected $findContainerPluginInstance;

    /**
     * @param FindContainerPluginInstance $findContainerPluginInstance
     */
    public function __construct(
        FindContainerPluginInstance $findContainerPluginInstance
    ) {
        $this->findContainerPluginInstance = $findContainerPluginInstance;
    }

    /**
     * @param int   $containerId
     * @param int   $pluginInstanceId
     * @param array $options
     *
     * @return null|PluginInstance
     */
    public function __invoke(
        $containerId,
        $pluginInstanceId,
        array $options = []
    ) {
        $pluginInstance = $this->findContainerPluginInstance->__invoke(
            $containerId,
            $pluginInstanceId
        );

        if (empty($pluginInstance)) {
            throw new PluginInstanceNotFoundException(
                'PluginInstance for instance id ' . $pluginInstanceId . ' not found.'
            );
        }

        return $pluginInstance->getInstanceConfig();
    }
}
