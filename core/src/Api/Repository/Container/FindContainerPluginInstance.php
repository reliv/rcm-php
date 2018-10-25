<?php

namespace Rcm\Api\Repository\Container;

use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindContainerPluginInstance
{
    protected $findContainer;

    /**
     * @param FindContainer $findContainer
     */
    public function __construct(
        FindContainer $findContainer
    ) {
        $this->findContainer = $findContainer;
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
        $container = $this->findContainer->__invoke(
            $containerId
        );

        if (empty($container)) {
            return null;
        }

        $pluginWrappers = $container->getPublishedRevision()->getPluginWrappers();

        $pluginWrappers->get($pluginInstanceId);
        /** @var PluginWrapper $pluginWrapper */
        foreach ($pluginWrappers as $pluginWrapper) {
            if ($pluginWrapper->getPluginInstanceId() == $pluginInstanceId) {
                return $pluginWrapper->getInstance();
            }
        }

        return null;
    }
}
