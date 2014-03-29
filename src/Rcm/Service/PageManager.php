<?php

namespace Rcm\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Rcm\Exception\PageNotFoundException;
use Zend\Cache\Storage\StorageInterface;
use Doctrine\ORM\NoResultException;

class PageManager extends ContainerAbstract
{

    protected $siteManager;
    protected $entityManager;
    protected $cache;

    protected $storedPages;
    protected $siteId;

    public function __construct(
        SiteManager $siteManager,
        PluginManager $pluginManager,
        EntityManager $entityManager,
        StorageInterface $cache
    ) {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
        
        $this->siteManager = $siteManager;
        $this->pluginManager = $pluginManager;

        $this->siteId = $this->siteManager->getCurrentSiteId();
    }
    
    public function getPageRevisionInfo($pageType, $pageName, $revision=null)
    {
        $siteId = $this->siteId;
        
        if (empty($revision)) {
            try {
                $revision = $this->getPagePublishedRevision($pageType, $pageName);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        if ($this->cache->hasItem('rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_'.$revision)) {
            return $this->cache->getItem('rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_'.$revision);
        }

        $pageInfo = $this->getPageRevisionDbInfo($pageType, $pageName, $revision);

        $this->getPluginRenderedInstances($pageInfo['revision']);

        $canCache = $this->canCacheRevision($pageInfo['revision']);

        if ($canCache) {
            $this->cache->setItem('rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_'.$revision, $pageInfo);
        }

        return $pageInfo;
    }
    
    public function getPagePublishedRevision($pageType, $pageName)
    {
        $siteId = $this->siteId;

        if ($this->cache->hasItem('rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_currentRevision')) {
            return $this->cache->getItem('rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_currentRevision');
        }

        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('currentRevision.revisionId')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.currentRevision', 'currentRevision')
            ->join('page.site', 'site')
            ->where('site.siteId = :siteId')
            ->andWhere('page.name = :pageName')
            ->setParameter('siteId', $siteId)
            ->setParameter('pageName', $pageName);

        try {
            $result = $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            throw new PageNotFoundException('No page revision found.', 1, $e);
        }

        $this->cache->setItem('rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_currentRevision', $result);

        return $result;
    }

    public function getPageStagedRevision($pageType, $pageName)
    {
        $siteId = $this->siteId;

        if ($this->cache->hasItem('rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_stagedRevision')) {
            return $this->cache->getItem('rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_stagedRevision');
        }

        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('stagedRevision.revisionId')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.stagedRevision', 'stagedRevision')
            ->join('page.site', 'site')
            ->where('site.siteId = :siteId')
            ->andWhere('page.name = :pageName')
            ->setParameter('siteId', $siteId)
            ->setParameter('name', $pageName);

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        $this->cache->setItem('rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_stagedRevision', $result);

        return $result;
    }

    public function getPageRevisionDbInfo($pageType, $pageName, $revisionId)
    {
        $siteId = $this->siteId;
        
        if (!empty($this->storedPages['data'][$siteId][$pageType][$pageName][$revisionId])) {
            return $this->storedPages['data'][$siteId][$pageType][$pageName][$revisionId];
        }
        
        if ($this->cache->hasItem('rcm_page_data_'.$siteId.'_'.$pageType.'_'.$pageName.'_'.$revisionId)) {
            return $this->cache->getItem('rcm_page_data_'.$siteId.'_'.$pageType.'_'.$pageName.'_'.$revisionId);
        }

        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select('
                page,
                currentRevision.revisionId currentRevisionId,
                stagedRevision.revisionId stagedRevisionId,
                revision,
                pluginWrappers,
                pluginInstances
            ')->from('\Rcm\Entity\Page','page')
            ->leftJoin('page.revisions', 'revision')
            ->leftJoin('page.currentRevision', 'currentRevision')
            ->leftJoin('page.stagedRevision', 'stagedRevision')
            ->leftJoin('revision.pluginInstances', 'pluginWrappers')
            ->leftJoin('pluginWrappers.instance', 'pluginInstances')
            ->where('page.site = :siteId')
            ->andWhere('page.pageType = :pageType')
            ->andWhere('page.name = :pageName')
            ->andWhere('revision.revisionId = :revisionId')
            ->setParameter('siteId', $siteId)
            ->setParameter('pageType', $pageType)
            ->setParameter('pageName', $pageName)
            ->setParameter('revisionId', $revisionId);

        try {
            $getData = $queryBuilder->getQuery()->getSingleResult(Query::HYDRATE_ARRAY);
        } catch (NoResultException $e) {
            throw new PageNotFoundException('No page revision found.', 1, $e);
        }

        $result = $getData[0];
        $result['revision'] = $result['revisions'][$revisionId];
        unset($result['revisions'], $getData);

        //$this->cache->setItem('rcm_page_data_'.$siteId.'_'.$pageType.'_'.$pageName.'_'.$revision, $result);
        $this->storedPages['data'][$siteId][$pageType][$pageName][$revisionId] = $result;

        return $result;
    }
}