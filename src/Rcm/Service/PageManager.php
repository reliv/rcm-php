<?php
/**
 * Rcm Page Manager
 *
 * This file contains the class definition for the Page Manager
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
 * @link      http://github.com/reliv
 */

namespace Rcm\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Rcm\Exception\PageNotFoundException;
use Zend\Cache\Storage\StorageInterface;
use Doctrine\ORM\NoResultException;

/**
 * Rcm Page Manager
 *
 * Rcm Page Manager.  This class handles everything about a CMS page.  Pages also
 * include their own plugin containers and can contain multiple containers depending
 * on the page template defined by the CMS theme being used.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 *
 */
class PageManager extends ContainerAbstract
{
    /** @var \Rcm\Service\SiteManager  */
    protected $siteManager;

    /** @var \Doctrine\ORM\EntityManagerInterface  */
    protected $entityManager;

    /** @var \Zend\Cache\Storage\StorageInterface  */
    protected $cache;

    /** @var array */
    protected $storedPages;

    /** @var integer */
    protected $siteId;

    /**
     * Constructor
     *
     * @param SiteManager            $siteManager   Rcm Site Manager
     * @param PluginManager          $pluginManager Rcm Plugin Manager
     * @param EntityManagerInterface $entityManager Doctrine Entity Manager
     * @param StorageInterface       $cache         Zend Cache Manager
     */
    public function __construct(
        SiteManager $siteManager,
        PluginManager $pluginManager,
        EntityManagerInterface $entityManager,
        StorageInterface $cache
    ) {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
        
        $this->siteManager = $siteManager;
        $this->pluginManager = $pluginManager;

        $this->siteId = $this->siteManager->getCurrentSiteId();
    }

    /**
     * Get All the Page revision info and cache if possible.
     *
     * @param string       $pageName Page Name
     * @param null|string  $pageType Type of page.  Type "n" is default
     * @param null|integer $revision Revision Id
     *
     * @return null|array
     * @throws \Exception
     */
    public function getPageRevisionInfo($pageName, $pageType='n', $revision=null)
    {
        $siteId = $this->siteId;
        $cacheKey = 'rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_'.$revision;
        
        if (empty($revision)) {
            try {
                $revision = $this->getPagePublishedRevision($pageName, $pageType);
            } catch (\Exception $e) {
                throw $e;
            }
        }

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $pageInfo = $this->getPageRevisionDbInfo($pageName, $pageType, $revision);

        $this->getPluginRenderedInstances($pageInfo['revision']);

        $canCache = $this->canCacheRevision($pageInfo['revision']);

        if ($canCache) {
            $this->cache->setItem($cacheKey, $pageInfo);
        }

        return $pageInfo;
    }

    /**
     * Get Pages Publish Revision ID
     *
     * @param string      $pageName Page Name
     * @param null|string $pageType Page Type. Type "n" is default
     *
     * @return null|integer
     * @throws \Rcm\Exception\PageNotFoundException
     */
    public function getPagePublishedRevision($pageName, $pageType='n')
    {
        $siteId = $this->siteId;
        $cacheKey
            = 'rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_currentRevision';

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('currentRevision.revisionId')
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

        $this->cache->setItem($cacheKey, $result);

        return $result;
    }

    /**
     * Get the Page Staged Revision Id and cache for later use
     *
     * @param string      $pageName Page Name
     * @param null|string $pageType Page Type.  Type "n" is default
     *
     * @return null|integer
     */
    public function getPageStagedRevision($pageName, $pageType='n')
    {
        $siteId = $this->siteId;
        $cacheKey
            = 'rcm_page_'.$siteId.'_'.$pageType.'_'.$pageName.'_stagedRevision';

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('stagedRevision.revisionId')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.stagedRevision', 'stagedRevision')
            ->join('page.site', 'site')
            ->where('site.siteId = :siteId')
            ->andWhere('page.name = :pageName')
            ->setParameter('siteId', $siteId)
            ->setParameter('name', $pageName);

        $result = $queryBuilder->getQuery()->getSingleScalarResult();

        $this->cache->setItem($cacheKey, $result);

        return $result;
    }

    /**
     * Get Page Revision DB Info and cache for later
     *
     * @param string $pageName   Page Name
     * @param string $pageType   Page Type
     * @param string $revisionId Revision Id
     *
     * @return null|array Database Result Set
     * @throws \Rcm\Exception\PageNotFoundException
     */
    public function getPageRevisionDbInfo($pageName, $pageType, $revisionId)
    {
        $siteId = $this->siteId;
        $storedPages = $this->storedPages;

        $cacheKey
            = 'rcm_page_data_'.$siteId.'_'.$pageType.'_'.$pageName.'_'.$revisionId;

        //@codingStandardsIgnoreStart
        if (!empty($storedPages['data'][$siteId][$pageType][$pageName][$revisionId])) {
            return $storedPages['data'][$siteId][$pageType][$pageName][$revisionId];
        }
        //@codingStandardsIgnoreEnd
        
        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }


        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select(
            'page,
            site.siteId,
            currentRevision.revisionId currentRevisionId,
            stagedRevision.revisionId stagedRevisionId,
            revision,
            pluginWrappers,
            pluginInstances'
        )->from('\Rcm\Entity\Page', 'page')
            ->leftJoin('page.site', 'site')
            ->leftJoin('page.revisions', 'revision')
            ->leftJoin('page.currentRevision', 'currentRevision')
            ->leftJoin('page.stagedRevision', 'stagedRevision')
            ->leftJoin('revision.pluginInstances', 'pluginWrappers')
            ->leftJoin('pluginWrappers.instance', 'pluginInstances')
            ->where('site.siteId = :siteId')
            ->andWhere('page.pageType = :pageType')
            ->andWhere('page.name = :pageName')
            ->andWhere('revision.revisionId = :revisionId')
            ->orderBy('pluginWrappers.layoutContainer')
            ->orderBy('pluginWrappers.renderOrder')
            ->setParameter('siteId', $siteId)
            ->setParameter('pageType', $pageType)
            ->setParameter('pageName', $pageName)
            ->setParameter('revisionId', $revisionId);

        try {
            $getData = $queryBuilder
                ->getQuery()
                ->getSingleResult(Query::HYDRATE_ARRAY);
        } catch (NoResultException $e) {
            throw new PageNotFoundException('No page revision found.', 1, $e);
        }

        $result = $getData[0];
        $result['revision'] = $result['revisions'][$revisionId];
        $result['siteId'] = $getData['siteId'];
        $result['currentRevisionId'] = $getData['currentRevisionId'];
        $result['stagedRevisionId'] = $getData['stagedRevisionId'];
        unset($result['revisions'], $getData);

        $this->cache->setItem($cacheKey, $result);

        $this->storedPages['data'][$siteId][$pageType][$pageName][$revisionId]
            = $result;

        return $result;
    }
}