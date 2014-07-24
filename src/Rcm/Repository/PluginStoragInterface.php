<?php
/**
 * Created by PhpStorm.
 * User: rmcnew
 * Date: 12/4/13
 * Time: 4:05 PM
 */

namespace Rcm\Repository;

interface PluginStorageRepoInterface
{
    public function select($instanceId);

    public function insert($instanceId, $configData);

    public function delete($instanceId);
}
