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
     *
     * @return PluginInstanceEntity New saved plugin instance
     */
    public function savePlugin(
        $pluginInstanceId,
        $pluginName,
        $saveData,
        $siteWide=false,
        $displayName=''
    ) {
        if ($pluginInstanceId > 0) {
            return $this->saveExistingPlugin($pluginInstanceId, $saveData);
        }

        return $this->saveNewInstance($pluginName, $saveData, $siteWide, $displayName);
    }

    public function saveExistingPlugin($pluginInstanceId, $saveData)
    {
        $pluginInstance = $this->findOneBy(array('pluginInstanceId' => $pluginInstanceId));

        if (empty($pluginInstance)) {
            throw new InvalidArgumentException('No plugin found for instance Id: '.$pluginInstanceId);
        }

        if ($pluginInstance->getMd5() == md5(serialize($saveData))) {
            return $pluginInstance;
        }

        $newPluginInstance = $this->saveNewInstance(
            $pluginInstance->getPlugin(),
            $saveData,
            $pluginInstance->isSiteWide(),
            $pluginInstance->getDisplayName()
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
     *
     * @return PluginInstanceEntity
     */
    public function saveNewInstance(
        $pluginName,
        $saveData,
        $siteWide = false,
        $displayName = null
    ) {
        $pluginInstance = new PluginInstanceEntity();
        $pluginInstance->setPlugin($pluginName);

        if(empty($displayName)) {
            $displayName = $pluginName;
        }

        if ($siteWide) {
            $pluginInstance->setSiteWide();

            if (!empty($displayName)) {
                $pluginInstance->setDisplayName($displayName);
            }
        }

        $pluginInstance->setMd5(md5(serialize($saveData)));
        $pluginInstance->setInstanceConfig($saveData);

        $this->_em->persist($pluginInstance);
        $this->_em->flush($pluginInstance);

        return $pluginInstance;
    }

}
