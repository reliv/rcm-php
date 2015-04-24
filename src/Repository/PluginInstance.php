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
use Rcm\Entity\PluginInstance as PluginInstanceEntity;
use Rcm\Entity\Site as SiteEntity;
use Rcm\Exception\InvalidArgumentException;

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
     * updatePlugin
     *
     * @param array      $pluginData
     * @param SiteEntity $site
     * @param bool       $forceSave
     * @param bool       $doFlush
     *
     * @return PluginInstanceEntity
     */
    public function updatePlugin(
        $pluginData,
        SiteEntity $site,
        $forceSave = false,
        $doFlush = true
    ) {
        $pluginData = $this->prepareData($pluginData);

        if ($pluginData['pluginInstanceId'] > 0) {

            return $this->updateExistingPlugin(
                $pluginData,
                $site,
                $forceSave,
                $doFlush
            );
        }

        return $this->createPluginInstance($pluginData, $site, null, $doFlush);
    }

    /**
     * updateExistingPlugin
     *
     * @param array      $pluginData
     * @param SiteEntity $site
     * @param bool       $forceSave
     * @param bool       $doFlush
     *
     * @return PluginInstanceEntity
     * @throws \InvalidArgumentException
     */
    public function updateExistingPlugin(
        $pluginData,
        SiteEntity $site,
        $forceSave = false,
        $doFlush = true
    ) {
        if (empty($pluginData['pluginInstanceId'])) {
            throw new InvalidArgumentException(
                'Plugin instance Id required to update.'
            );
        }

        /** @var PluginInstanceEntity $pluginInstance */
        $pluginInstance = $this->findOneBy(
            ['pluginInstanceId' => $pluginData['pluginInstanceId']]
        );

        if (empty($pluginInstance)) {
            throw new InvalidArgumentException(
                'No plugin found for instance Id: '
                . $pluginData['pluginInstanceId']
            );
        }

        $pluginData['plugin'] = $pluginInstance->getPlugin();

        $pluginData = $this->prepareData($pluginData);

        if (!$forceSave
            && $pluginInstance->getMd5() == md5(serialize($pluginData['saveData']))
            && $pluginInstance->isSiteWide() == (bool) $pluginData['siteWide']
        ) {
            return $pluginInstance;
        }

        $newPluginInstance = $this->createPluginInstance(
            $pluginData,
            $site,
            $pluginInstance,
            $doFlush
        );

        return $newPluginInstance;
    }

    /**
     * createPluginInstance
     *
     * @param array      $pluginData
     * @param SiteEntity $site
     * @param null       $oldPluginInstance
     * @param bool       $doFlush
     *
     * @return PluginInstanceEntity
     */
    public function createPluginInstance(
        $pluginData,
        SiteEntity $site,
        $oldPluginInstance = null,
        $doFlush = true
    ) {
        $pluginInstance = new PluginInstanceEntity();
        $pluginInstance->populate($pluginData);

        $this->updateSiteSitewide(
            $pluginInstance,
            $site,
            $oldPluginInstance
        );

        $this->_em->persist($pluginInstance);

        if ($doFlush) {
            $this->_em->flush();
        }

        return $pluginInstance;
    }

    /**
     * updateSiteSitewide
     *
     * @param PluginInstanceEntity $pluginInstance
     * @param SiteEntity           $site
     * @param null                 $oldPluginInstance
     *
     * @return void
     */
    public function updateSiteSitewide(
        PluginInstanceEntity $pluginInstance,
        SiteEntity $site,
        $oldPluginInstance = null
    ) {
        // ignore non-sitewides
        if(!$pluginInstance->isSiteWide()){
            return;
        }

        if(!empty($oldPluginInstance)) {
            $site->removeSiteWidePlugin($oldPluginInstance);
        }

        $site->addSiteWidePlugin($pluginInstance);

        $this->_em->persist($site);
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
        if (array_key_exists('instanceId', $pluginData)) {
            $pluginData['pluginInstanceId'] = (int)$pluginData['instanceId'];
        }

        if (array_key_exists('sitewideName', $pluginData)) {
            $pluginData['displayName'] = $pluginData['sitewideName'];
        }

        if (array_key_exists('name', $pluginData)) {
            $pluginData['plugin'] = $pluginData['name'];
        }

        if (array_key_exists('isSitewide', $pluginData)) {
            $pluginData['siteWide'] = $pluginData['isSitewide'];
        }

        // Defaults
        if (!isset($pluginData['displayName'])
            && !empty($pluginData['siteWide'])
            && !empty($pluginData['plugin'])
        ) {
            $pluginData['displayName'] = $pluginData['plugin'];
        }

        if (!isset($pluginData['pluginInstanceId'])) {
            $pluginData['pluginInstanceId'] = 0;
        }

        return $pluginData;
    }

}
