<?php

namespace Rcm\Interfaces;


interface PluginManagerInterface
{
    public function getNewEntity($pluginName);

    public function getPluginByInstanceId($instanceId);

    public function getPluginViewData($pluginName, $instanceId);

    public function savePlugin($instanceId, $saveData);

    public function saveNewInstance(
        $pluginName, $saveData, $siteWide, $displayName
    );

    public function getPluginController($pluginName);

    public function deletePluginInstance($instanceId);

}
