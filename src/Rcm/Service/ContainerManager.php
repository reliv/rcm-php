<?php

namespace Rcm\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Rcm\Exception\ContainerNotFoundException;
use Zend\Cache\Storage\StorageInterface;

class ContainerManager extends ContainerAbstract
{
    protected $siteManager;
    protected $pluginManager;
    protected $entityManager;
    protected $cache;
    protected $siteId;

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

    public function getContainerByName($name, $revision=null)
    {
        $siteId = $this->siteId;

        if (empty($revision)) {
            $revision = $this->getPublishedRevisionId($name);
        }

        if ($this->cache->hasItem('rcm_container_'.$siteId.'_'.$name.'_'.$revision)) {
            return $this->cache->getItem('rcm_container_'.$siteId.'_'.$name.'_'.$revision);
        }

        $containerData = $this->getContainerDataByName($name, $revision);
        $this->getPluginRenderedInstances($containerData['revision']);

        $canCache = $this->canCacheRevision($containerData['revision']);

        if ($canCache) {
            $this->cache->setItem('rcm_container_'.$siteId.'_'.$name.'_'.$revision, $containerData);
        }

        return $containerData;
    }

    protected function getPublishedRevisionId($name)
    {
        $siteId = $this->siteId;

        if ($this->cache->hasItem('rcm_container_'.$siteId.'_'.$name.'_currentRevision')) {
            return $this->cache->getItem('rcm_container_'.$siteId.'_'.$name.'_currentRevision');
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

        $this->cache->setItem('rcm_container_'.$siteId.'_'.$name.'_currentRevision', $result);

        return $result;
    }


    protected function getContainerDataByName($name, $revisionId)
    {
        $siteId = $this->siteId;

        if ($this->cache->hasItem('rcm_container_data_'.$siteId.'_'.$name.'_'.$revisionId)) {
            return $this->cache->getItem('rcm_container_data_'.$siteId.'_'.$name.'_'.$revisionId);
        }

        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('container, currentRevision.revisionId, revision, pluginWrappers, pluginInstances')
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
            throw new ContainerNotFoundException('No Container data found by name: '.$name);
        }

        $this->cache->setItem('rcm_container_data_'.$siteId.'_'.$name.'_'.$revisionId, $result);

        return $result;
    }


}