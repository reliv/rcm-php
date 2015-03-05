<?php

/**
 * PluginWrapper Repository
 *
 * This file contains the PluginWrapper repository
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
use Rcm\Entity\PluginWrapper as PluginWrapperEntity;
use Rcm\Entity\Site as SiteEntity;
use Rcm\Exception\RuntimeException;

/**
 * PluginWrapper Repository
 *
 * PluginWrapper Repository.  Used to get custom page results from the DB
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
class PluginWrapper extends EntityRepository
{
    /**
     * savePluginWrapper Save a plugin wrapper
     *
     * @param array $pluginData
     * @param SiteEntity  $site
     * @param null  $oldWrapper
     *
     * @return null|PluginWrapperEntity
     */
    public function savePluginWrapper($pluginData, SiteEntity $site, $oldWrapper=null)
    {
        if (!empty($oldWrapper) && !is_a($oldWrapper, '\Rcm\Entity\PluginWrapper')) {
            throw new RuntimeException('Wrapper passed in is not a valid plugin wrapper.');
        }

        /** @var \Rcm\Repository\PluginInstance $pluginInstanceRepo */
        $pluginInstanceRepo = $this->_em->getRepository('\Rcm\Entity\PluginInstance');

        $pluginData = $this->prepareData($pluginData);

        $pluginInstance = $pluginInstanceRepo->updatePlugin(
            $pluginData,
            $site
        );

        if (!empty($oldWrapper)
            && ($pluginData['siteWide'] || $oldWrapper->getInstance()->isSiteWide())
            && $pluginInstance->getInstanceId() != $oldWrapper->getInstance()->getInstanceId()
        ) {
            $queryBuilder = $this->_em->createQueryBuilder();
            $queryBuilder->update('\Rcm\Entity\PluginWrapper', 'wrapper')
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
            && ($oldWrapper->getInstance()->getInstanceId() == $pluginInstance->getInstanceId()
            || $pluginData['siteWide'])
        ) {
            return $oldWrapper;
        }

        $pluginWrapper = new PluginWrapperEntity();
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
        if(!isset($pluginData['layoutContainer']) && array_key_exists('containerName', $pluginData)){
            $pluginData['layoutContainer'] = $pluginData['containerName'];
        }

        if(!isset($pluginData['renderOrder']) && array_key_exists('rank', $pluginData)){
            $pluginData['renderOrder'] = $pluginData['rank'];
        }

        // Defaults
        if(!isset($pluginData['layoutContainer'])){
            $pluginData['layoutContainer'] = null;
        }

        if(!isset($pluginData['siteWide'])){
            $pluginData['siteWide'] = 0;
        }

        if(!isset($pluginData['renderOrder'])){
            $pluginData['renderOrder'] = 0;
        }

        /** @var \Rcm\Repository\PluginInstance $pluginInstanceRepo */
        $pluginInstanceRepo = $this->_em->getRepository('\Rcm\Entity\PluginInstance');

        return $pluginInstanceRepo->prepareData($pluginData);
    }

}
