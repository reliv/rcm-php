<?php

namespace Rcm\ImmutableHistory\Page;

use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;

class PageContentFactory
{
    /**
     * @param string $title
     * @param string | null $description
     * @param string | null $keywords
     * @param array $pluginWrappers
     * @return PageContent
     */
    public function __invoke(
        string $title,
        $description,
        $keywords,
        array $pluginWrappers,
        bool $publicReadAccess,
        $readAccessGroups
    ) {
        return new PageContent(
            $title,
            $description,
            $keywords,
            $this->pluginWrappersToFlatBlockInstances($pluginWrappers),
            $publicReadAccess,
            $readAccessGroups
        );
    }

    public function pluginWrappersToFlatBlockInstances(array $pluginWrappers)
    {
        $pluginWrapperToImmutableFlatBlockInstanceData = function (PluginWrapper $wrapper) {
            /**
             * @var PluginInstance
             */
            $instance = $wrapper->getInstance();

            return [
                'layoutContainer' => $wrapper->getLayoutContainer(),
                'rowNumber' => $wrapper->getRowNumber(),
                'renderOrder' => $wrapper->getRenderOrder(),
                'columnClass' => $wrapper->getColumnClass(),
                'blockName' => $instance->getPlugin(),
                'instanceConfig' => $instance->getInstanceConfig()
            ];
        };

        return array_map($pluginWrapperToImmutableFlatBlockInstanceData, $pluginWrappers);
    }
}
