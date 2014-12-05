<?php

/**
 * PluginInstance Repository
 *
 * This file contains the PluginInstance repository
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Entity\PluginInstance as PluginInstanceEntity;

/**
 * Page Repository
 *
 * Page Repository.  Used to get custom page results from the DB
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class PluginInstance extends EntityRepository
{
    /**
     * Save a plugin instance
     *
     * @param integer $pluginInstanceId Current Instance Id
     * @param string  $pluginName       Plugin Name
     * @param mixed   $saveData         Plugin Data to Save
     * @param boolean $siteWide         Is this a site wide
     * @param string  $displayName      Plugin name for site wide
     * @param bool   $doFlush
     *
     * @return PluginInstanceEntity New saved plugin instance
     */
    public function savePlugin(
        $pluginInstanceId,
        $pluginName,
        $saveData,
        $siteWide=false,
        $displayName='',
        $doFlush = true
    ) {
        if ($pluginInstanceId > 0) {
            return $this->saveExistingPlugin($pluginInstanceId, $saveData, $doFlush);
        }

        return $this->saveNewInstance($pluginName, $saveData, $siteWide, $displayName, $doFlush);
    }

    /**
     * updatePlugin
     *
     * @param array $pluginData
     * @param bool $forceSave
     * @param bool $doFlush
     *
     * @return PluginInstanceEntity
     * @throws \Exception
     */
    public function updatePlugin(
        $pluginData,
        $forceSave = false,
        $doFlush = true
    ) {
        $pluginData = $this->prepareData($pluginData);

        if ($pluginData['pluginInstanceId'] > 0) {
            return $this->updateExistingPlugin($pluginData, $forceSave, $doFlush);
        }

        return $this->createPluginInstance($pluginData, $doFlush);

    }

    /**
     * saveExistingPlugin
     *
     * @param $pluginInstanceId
     * @param $saveData
     *
     * @return null|object|PluginInstanceEntity
     */
    public function saveExistingPlugin($pluginInstanceId, $saveData, $doFlush = true)
    {
        $pluginData = [];
        $pluginData['pluginInstanceId'] = $pluginInstanceId;
        $pluginData['saveData'] = $saveData;
        $pluginData = $this->prepareData($pluginData);

        $newPluginInstance = $this->updatePlugin(
            $pluginData,
            false,
            $doFlush
        );

        return $newPluginInstance;
    }

    /**
     * updateExistingPlugin
     *
     * @param array $pluginData
     * @param bool $forceSave
     * @param bool $doFlush
     *
     * @return PluginInstanceEntity
     * @throws \Exception
     */
    public function updateExistingPlugin(
        $pluginData,
        $forceSave = false,
        $doFlush = true
    ){
        if (empty($pluginData['pluginInstanceId'])) {
            throw new InvalidArgumentException('Plugin instance Id required to update.');
        }

        /** @var PluginInstanceEntity $pluginInstance */
        $pluginInstance = $this->findOneBy(['pluginInstanceId' => $pluginData['pluginInstanceId']]);

        if (empty($pluginInstance)) {
            throw new InvalidArgumentException('No plugin found for instance Id: '.$pluginData['pluginInstanceId']);
        }

        $pluginData['plugin'] = $pluginInstance->getPlugin();

        $pluginData = $this->prepareData($pluginData);

        if (!$forceSave && $pluginInstance->getMd5() == md5(serialize($pluginData['saveData']))) {
            return $pluginInstance;
        }

        $newPluginInstance = $this->createPluginInstance(
            $pluginData,
            $doFlush
        );

        return $newPluginInstance;
    }

    /**
     * Save a new plugin instance
     *
     * @param string      $pluginName  Plugin name
     * @param array       $saveData    Save Data
     * @param bool        $siteWide    Site Wide marker
     * @param null|string $displayName Display name for site wide plugins.  Required
     *                                 for site wide plugin instances.
     * @param bool $doFlush
     *
     * @return PluginInstanceEntity
     * @throws \Exception
     */
    public function saveNewInstance(
        $pluginName,
        $saveData,
        $siteWide = false,
        $displayName = null,
        $doFlush = true
    ) {
        if(empty($displayName) && $siteWide){
            throw new \Exception('SiteWide plugin requires a display name to be created.');
        }

        $pluginData['plugin'] = $pluginName;
        $pluginData['saveData'] = $saveData;
        $pluginData['siteWide'] = $siteWide;
        $pluginData['displayName'] = $displayName;
        $pluginData = $this->prepareData($pluginData);

        return $this->createPluginInstance($pluginData, $doFlush);
    }

    /**
     * createPluginInstance
     *
     * @param      $pluginData
     * @param bool $doFlush
     *
     * @return PluginInstanceEntity
     * @throws \Exception
     */
    public function createPluginInstance(
        $pluginData,
        $doFlush = true
    ) {
        $pluginInstance = new PluginInstanceEntity();
        $pluginInstance->populate($pluginData);

        $this->_em->persist($pluginInstance);

        if($doFlush) {
            $this->_em->flush($pluginInstance);
        }

        return $pluginInstance;
    }

    /**
     * prepareData
     *
     * @param array $pluginData
     *
     * @return array
     */
    public function prepareData($pluginData = [])
    {
        // Data migration of alternate keys
        if(!isset($pluginData['pluginInstanceId']) && array_key_exists('instanceId',$pluginData)){
            $pluginData['pluginInstanceId'] = $pluginData['instanceId'];
        }

        if(!isset($pluginData['displayName']) && array_key_exists('sitewideName', $pluginData)){
            $pluginData['displayName'] = $pluginData['sitewideName'];
        }

        if(!isset($pluginData['plugin']) && array_key_exists('name', $pluginData)){
            $pluginData['plugin'] = $pluginData['name'];
        }

        if(!isset($pluginData['siteWide']) && array_key_exists('isSitewide', $pluginData)){
            $pluginData['siteWide'] = $pluginData['isSitewide'];
        }

        // Defaults
        if(!isset($pluginData['displayName']) && !empty($pluginData['siteWide']) && !empty($pluginData['plugin'])) {
            $pluginData['displayName'] = $pluginData['plugin'];
        }

        if(!isset($pluginData['pluginInstanceId'])){
            $pluginData['pluginInstanceId'] = 0;
        }

        return $pluginData;
    }

}
