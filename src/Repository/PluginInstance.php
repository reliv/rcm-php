<?php

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Rcm\Entity\PluginInstance as PluginInstanceEntity;
use Rcm\Entity\Site as SiteEntity;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Tracking\Model\Tracking;

/**
 * PluginInstance.  Used to get custom page results from the DB
 *
 * PHP version 5
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
     * @param            $pluginData
     * @param SiteEntity $site
     * @param string     $modifiedByUserId
     * @param string     $modifiedReason
     * @param bool       $forceSave
     * @param bool       $doFlush
     *
     * @return PluginInstanceEntity
     */
    public function updatePlugin(
        $pluginData,
        SiteEntity $site,
        string $modifiedByUserId,
        string $modifiedReason = Tracking::UNKNOWN_REASON,
        $forceSave = false,
        $doFlush = true
    ) {
        $pluginData = $this->prepareData($pluginData);

        if ($pluginData['pluginInstanceId'] > 0) {
            return $this->updateExistingPlugin(
                $pluginData,
                $site,
                $modifiedByUserId,
                $modifiedReason,
                $forceSave,
                $doFlush
            );
        }

        return $this->createPluginInstance(
            $pluginData,
            $site,
            $modifiedByUserId,
            $modifiedReason,
            null,
            $doFlush
        );
    }

    /**
     * @param array      $pluginData
     * @param SiteEntity $site
     * @param string     $modifiedByUserId
     * @param string     $modifiedReason
     * @param bool       $forceSave
     * @param bool       $doFlush
     *
     * @return PluginInstanceEntity
     */
    public function updateExistingPlugin(
        $pluginData,
        SiteEntity $site,
        string $modifiedByUserId,
        string $modifiedReason = Tracking::UNKNOWN_REASON,
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
            && $pluginInstance->isSiteWide() == (bool)$pluginData['siteWide'] // @deprecated <deprecated-site-wide-plugin>
        ) {
            return $pluginInstance;
        }

        $newPluginInstance = $this->createPluginInstance(
            $pluginData,
            $site,
            $modifiedByUserId,
            $modifiedReason,
            $pluginInstance,
            $doFlush
        );

        return $newPluginInstance;
    }

    /**
     * @param array      $pluginData
     * @param SiteEntity $site
     * @param string     $createdByUserId
     * @param string     $createdReason
     * @param null       $oldPluginInstance
     * @param bool       $doFlush
     *
     * @return PluginInstanceEntity
     */
    public function createPluginInstance(
        $pluginData,
        SiteEntity $site,
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON,
        $oldPluginInstance = null,
        $doFlush = true
    ) {
        $pluginInstance = new PluginInstanceEntity(
            $createdByUserId,
            $createdReason
        );
        $pluginInstance->populate($pluginData);

        // @deprecated <deprecated-site-wide-plugin>
        $this->updateSiteSitewide(
            $pluginInstance,
            $site,
            $oldPluginInstance
        );

        $this->_em->persist($pluginInstance);

        if ($doFlush) {
            $this->_em->flush($pluginInstance);
        }

        return $pluginInstance;
    }

    /**
     *  @deprecated <deprecated-site-wide-plugin>
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
        // @deprecated <deprecated-site-wide-plugin>
        if (!$pluginInstance->isSiteWide()) {
            return;
        }

        if (!empty($oldPluginInstance)) {
            // @deprecated <deprecated-site-wide-plugin>
            $site->removeSiteWidePlugin($oldPluginInstance);
        }

        //  @deprecated <deprecated-site-wide-plugin>
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

        // @deprecated <deprecated-site-wide-plugin>
        if (array_key_exists('sitewideName', $pluginData)) {
            $pluginData['displayName'] = $pluginData['sitewideName'];
        }

        if (array_key_exists('name', $pluginData)) {
            $pluginData['plugin'] = $pluginData['name'];
        }

        // @deprecated <deprecated-site-wide-plugin>
        if (array_key_exists('isSitewide', $pluginData)) {
            $pluginData['siteWide'] = $pluginData['isSitewide'];
        }

        // Defaults
        if (!isset($pluginData['displayName'])
            && !empty($pluginData['siteWide']) // @deprecated <deprecated-site-wide-plugin>
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
