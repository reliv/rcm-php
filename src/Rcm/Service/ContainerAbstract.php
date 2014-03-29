<?php

namespace Rcm\Service;

abstract class ContainerAbstract
{
    /** @var \Rcm\Service\PluginManager */
    protected $pluginManager;

    protected function getPluginRenderedInstances(&$revisionData)
    {
        foreach ($revisionData['pluginInstances'] as &$pluginWrapper)
        {
            $pluginWrapper['instance']['renderedData']
                = $this->pluginManager->getPluginByInstanceId($pluginWrapper['instance']['pluginInstanceId']);
        }
    }

    protected function canCacheRevision(&$revisionData)
    {
        $canCache = true;

        foreach ($revisionData['pluginInstances'] as &$pluginWrapper)
        {
            if (empty($pluginWrapper['instance']['canCache'])) {
                $canCache = false;
            }
        }

        return $canCache;
    }
}