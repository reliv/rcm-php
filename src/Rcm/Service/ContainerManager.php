<?php
/**
 * Container Manager
 *
 * This file contains the class used to manage plugin containers.
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

namespace Rcm\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Rcm\Exception\ContainerNotFoundException;
use Zend\Cache\Storage\StorageInterface;

/**
 * Plugin Container Manager.
 *
 * The Plugin Container Manager is used to manage containers in the CMS.  These
 * containers can be used by anything that wants to have a container or a set
 * of CMS plugin instances.
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
class ContainerManager extends ContainerAbstract
{
    protected $siteManager;
    protected $pluginManager;
    protected $entityManager;
    protected $cache;
    protected $siteId;

    /**
     * Constructor
     *
     * @param SiteManager      $siteManager   Rcm Site Manager
     * @param PluginManager    $pluginManager Rcm Plugin Manager
     * @param EntityManager    $entityManager Doctrine Entity Manager
     * @param StorageInterface $cache         Zend Cache Manager
     */
    public function __construct(
        SiteManager $siteManager,
        PluginManager $pluginManager,
        EntityManager $entityManager,
        StorageInterface $cache
    ) {
        $this->siteManager = $siteManager;
        $this->pluginManager = $pluginManager;
        $this->entityManager = $entityManager;
        $this->cache = $cache;

        $this->siteId = $this->siteManager->getCurrentSiteId();
    }

    /**
     * Get a container by name.  Pass in a revision number of the container to get
     * a specific version of the requested container.
     *
     * @param string       $name     Name of the container to lookup
     * @param null|integer $revision Revision Number of the container to find.
     *
     * @return mixed
     */
    public function getContainerByName($name, $revision=null)
    {
        $siteId = $this->siteId;
        $cacheKey = 'rcm_container_'.$siteId.'_'.$name.'_'.$revision;

        if (empty($revision)) {
            $revision = $this->getPublishedRevisionId($name);
        }

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $containerData = $this->getContainerDataByName($name, $revision);
        $this->getPluginRenderedInstances($containerData['revision']);

        $canCache = $this->canCacheRevision($containerData['revision']);

        if ($canCache) {
            $this->cache->setItem($cacheKey, $containerData);
        }

        return $containerData;
    }

    /**
     * Get the latest Published revision for a container.
     *
     * @param string $name Name of container to lookup.
     *
     * @return mixed
     */
    protected function getPublishedRevisionId($name)
    {
        $siteId = $this->siteId;
        $cacheKey = 'rcm_container_'.$siteId.'_'.$name.'_currentRevision';

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('currentRevision.revisionId')
            ->from('\Rcm\Entity\Container', 'container')
            ->join('container.currentRevision', 'currentRevision')
            ->join('container.site', 'site')
            ->where('site.siteId = :siteId')
            ->andWhere('container.name = :containerName')
            ->setParameter('siteId', $siteId)
            ->setParameter('containerName', $name);

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        $this->cache->setItem($cacheKey, $result);

        return $result;
    }

    /**
     * Get a containers database data by container name.
     *
     * @param string  $name       Name of container
     * @param integer $revisionId Revision Id
     *
     * @return mixed
     * @throws \Rcm\Exception\ContainerNotFoundException
     */
    protected function getContainerDataByName($name, $revisionId)
    {
        $siteId = $this->siteId;
        $cacheKey = 'rcm_container_data_'.$siteId.'_'.$name.'_'.$revisionId;

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select(
                'container,'
                .'currentRevision.revisionId,'
                .'revision,'
                .'pluginWrappers,'
                .'pluginInstances'
            )
            ->from('\Rcm\Entity\Container', 'container')
            ->leftJoin('container.currentRevision', 'currentRevision')
            ->leftJoin('container.site', 'site')
            ->leftJoin('container.revisions', 'revision')
            ->leftJoin('revision.pluginInstances', 'pluginWrappers')
            ->leftJoin('pluginWrappers.instance', 'pluginInstances')
            ->where('site.siteId = :siteId')
            ->andWhere('container.name = :containerName')
            ->andWhere('revision.revisionId = :revisionId')
            ->orderBy('pluginWrappers.renderOrder', 'ASC')
            ->setParameter('siteId', $siteId)
            ->setParameter('containerName', $name)
            ->setParameter('revisionId', $revisionId);

        $getData = $queryBuilder->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);
        $result = $getData[0];

        $result['revision'] = $result['revisions'][$revisionId];

        unset($result['revisions'], $getData);

        if (empty($result)) {
            throw new ContainerNotFoundException(
                'No Container data found by name: '.$name
            );
        }

        $this->cache->setItem($cacheKey, $result);

        return $result;
    }

}