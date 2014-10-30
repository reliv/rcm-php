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
     * Save a plugin wrapper
     *
     * @param                    $pluginData
     * @param null|PluginWrapperEntity $oldWrapper
     *
     * @returns PluginWrapperEntity
     *
     * @throws \Rcm\Exception\RuntimeException
     */

    public function savePluginWrapper($pluginData, $oldWrapper=null)
    {
        if (!empty($oldWrapper) && !is_a($oldWrapper, '\Rcm\Entity\PluginWrapper')) {
            throw new RuntimeException('Wrapper passed in is not a valid plugin wrapper.');
        }

        /** @var \Rcm\Repository\PluginInstance $pluginInstanceRepo */
        $pluginInstanceRepo = $this->_em->getRepository('\Rcm\Entity\PluginInstance');

        $pluginInstance = $pluginInstanceRepo->savePlugin(
            $pluginData['instanceId'],
            $pluginData['name'],
            $pluginData['saveData'],
            $pluginData['isSitewide'],
            $pluginData['sitewideName']
        );

        if (!empty($oldWrapper)
            && ($pluginData['isSitewide'] || $oldWrapper->getInstance()->isSiteWide())
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
            && $oldWrapper->getDivFloat() == $pluginData['float']
            && $oldWrapper->getHeight() == $pluginData['height']
            && $oldWrapper->getWidth() == $pluginData['width']
            && $oldWrapper->getLayoutContainer() == $pluginData['containerName']
            && ($oldWrapper->getInstance()->getInstanceId() == $pluginInstance->getInstanceId()
                || $pluginInstance->isSiteWide())
        ) {
            return $oldWrapper;
        }

        $pluginWrapper = new PluginWrapperEntity();
        $pluginWrapper->setDivFloat($pluginData['float']);
        $pluginWrapper->setHeight($pluginData['height']);
        $pluginWrapper->setWidth($pluginData['width']);
        $pluginWrapper->setLayoutContainer($pluginData['containerName']);
        $pluginWrapper->setInstance($pluginInstance);
        $pluginWrapper->setRenderOrderNumber($pluginData['rank']);

        $this->_em->persist($pluginWrapper);
        $this->_em->flush($pluginWrapper);
        return $pluginWrapper;
    }
}
