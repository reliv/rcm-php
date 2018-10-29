<?php


namespace Rcm\ImmutableHistory\Page;


use Rcm\Entity\PluginInstance;
use Rcm\Entity\PluginWrapper;

class PageContentFactory
{
    public function __invoke(string $title, string $description, string $keywords, array $pluginWrappers)
    {
        $pluginWrapperToImmutableFlatBlockInstanceData = function (PluginWrapper $wrapper) {
            /**
             * @var PluginInstance
             */
            $instance = $wrapper->getInstance();

            //@TODO double check that everything needed to render is in here
            return [
                'layoutContainer' => $wrapper->getLayoutContainer(),
                'rowNumber' => $wrapper->getRowNumber(),
                'renderOrder' => $wrapper->getRenderOrder(),
                'columnClass' => $wrapper->getColumnClass(),
                'blockName' => $instance->getPlugin(),
                'instanceConfig' => $instance->getInstanceConfig()
            ];
        };

        return new PageContentDataModel(
            $title,
            $description,
            $keywords,
            array_map($pluginWrapperToImmutableFlatBlockInstanceData, $pluginWrappers)
        );
    }
}
