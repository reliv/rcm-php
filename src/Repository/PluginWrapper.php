<?php

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Rcm\Entity\PluginWrapper as PluginWrapperEntity;
use Rcm\Entity\Site as SiteEntity;
use Rcm\Exception\RuntimeException;
use Rcm\Tracking\Model\Tracking;

/**
 * PluginWrapper Repository
 *
 * PluginWrapper Repository.  Used to get custom page results from the DB
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
class PluginWrapper extends EntityRepository
{
    /**
     * @param array                          $pluginData
     * @param SiteEntity                     $site
     * @param string                         $modifiedByUserId
     * @param string                         $modifiedReason
     * @param null|\Rcm\Entity\PluginWrapper $oldWrapper
     *
     * @return null|PluginWrapperEntity
     */
    public function savePluginWrapper(
        $pluginData,
        SiteEntity $site,
        string $modifiedByUserId,
        string $modifiedReason = Tracking::UNKNOWN_REASON,
        $oldWrapper = null
    ) {
        if (!empty($oldWrapper) && !is_a($oldWrapper, \Rcm\Entity\PluginWrapper::class)) {
            throw new RuntimeException(
                'Wrapper passed in is not a valid plugin wrapper.'
            );
        }

        /** @var \Rcm\Repository\PluginInstance $pluginInstanceRepo */
        $pluginInstanceRepo = $this->_em->getRepository(
            \Rcm\Entity\PluginInstance::class
        );

        $pluginData = $this->prepareData($pluginData);

        $pluginInstance = $pluginInstanceRepo->updatePlugin(
            $pluginData,
            $site,
            $modifiedByUserId,
            $modifiedReason
        );

        if (!empty($oldWrapper)
            && ($pluginData['siteWide'] || $oldWrapper->getInstance()->isSiteWide()) // @deprecated <deprecated-site-wide-plugin>
            && $pluginInstance->getInstanceId() != $oldWrapper->getInstance()
                ->getInstanceId()
        ) {
            $queryBuilder = $this->_em->createQueryBuilder();
            $queryBuilder->update(\Rcm\Entity\PluginWrapper::class, 'wrapper')
                ->set('wrapper.instance', $pluginInstance->getInstanceId())
                ->where('wrapper.instance = :oldInstance')
                ->setParameter('oldInstance', $oldWrapper->getInstance());

            $queryBuilder->getQuery()->execute();
        }

        if (!empty($oldWrapper)
            && $oldWrapper->getRenderOrderNumber() == $pluginData['rank']
            && $oldWrapper->getRowNumber() == $pluginData['rowNumber']
            && $oldWrapper->getColumnClass() == $pluginData['columnClass']
            && $oldWrapper->getLayoutContainer() == $pluginData['containerName']
            && ($oldWrapper->getInstance()->getInstanceId()
                == $pluginInstance->getInstanceId()
                || $pluginData['siteWide']) // @deprecated <deprecated-site-wide-plugin>
        ) {
            return $oldWrapper;
        }

        $pluginWrapper = new PluginWrapperEntity(
            $modifiedByUserId,
            $modifiedReason
        );
        $pluginWrapper->populate($pluginData);
        $pluginWrapper->setInstance($pluginInstance);

        $this->_em->persist($pluginWrapper);
        $this->_em->flush($pluginWrapper);

        return $pluginWrapper;
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
        if (!isset($pluginData['layoutContainer'])
            && array_key_exists(
                'containerName',
                $pluginData
            )
        ) {
            $pluginData['layoutContainer'] = $pluginData['containerName'];
        }

        if (!isset($pluginData['renderOrder'])
            && array_key_exists(
                'rank',
                $pluginData
            )
        ) {
            $pluginData['renderOrder'] = $pluginData['rank'];
        }

        // Defaults
        if (!isset($pluginData['layoutContainer'])) {
            $pluginData['layoutContainer'] = null;
        }

        // @deprecated <deprecated-site-wide-plugin>
        if (!isset($pluginData['siteWide'])) {
            $pluginData['siteWide'] = 0;
        }

        if (!isset($pluginData['renderOrder'])) {
            $pluginData['renderOrder'] = 0;
        }

        /** @var \Rcm\Repository\PluginInstance $pluginInstanceRepo */
        $pluginInstanceRepo = $this->_em->getRepository(
            \Rcm\Entity\PluginInstance::class
        );

        return $pluginInstanceRepo->prepareData($pluginData);
    }
}
