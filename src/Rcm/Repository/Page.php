<?php

/**
 * Page Repository
 *
 * This file contains the page repository
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
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;


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
class Page extends EntityRepository implements ContainerInterface
{
    /**
     * Gets the DB result of the current Published Revision
     *
     * @param integer $siteId Site Id
     * @param string  $name   Name of the container
     * @param string  $type   Type of the container.  Currently only used by the page
     *                        container.
     *
     * @return mixed
     */
    public function getPublishedRevisionId($siteId, $name, $type='n')
    {
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('currentRevision.revisionId')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.currentRevision', 'currentRevision')
            ->join('page.site', 'site')
            ->where('site.siteId = :siteId')
            ->andWhere('page.name = :pageName')
            ->andWhere('page.pageType = :pageType')
            ->setParameter('siteId', $siteId)
            ->setParameter('pageName', $name)
            ->setParameter('pageType', $type);

        try {
            return $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Get the Page Staged Revision Id and cache for later use
     *
     * @param integer     $siteId Site Id
     * @param string      $name   Page Name
     * @param null|string $type   Page Type.  Type "n" is default
     *
     * @return null|integer
     */
    public function getStagedRevisionId($siteId, $name, $type='n')
    {
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('stagedRevision.revisionId')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.stagedRevision', 'stagedRevision')
            ->join('page.site', 'site')
            ->where('site.siteId = :siteId')
            ->andWhere('page.name = :pageName')
            ->andWhere('page.pageType = :pageType')
            ->setParameter('siteId', $siteId)
            ->setParameter('pageName', $name)
            ->setParameter('pageType', $type);

        try {
            return $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Get Revision DB Info
     *
     * @param integer $siteId     Site Id
     * @param string  $name       Page Name
     * @param string  $revisionId Revision Id
     * @param string  $type       Container Type.
     *
     * @return null|array Database Result Set
     */
    public function getRevisionDbInfo($siteId, $name, $revisionId, $type='n')
    {
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();
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
            ->setParameter('pageType', $type)
            ->setParameter('pageName', $name)
            ->setParameter('revisionId', $revisionId);

        $getData = $queryBuilder
            ->getQuery()
            ->getSingleResult(Query::HYDRATE_ARRAY);


        $result = null;

        if (!empty($getData)) {
            $result = $getData[0];
            $result['revision'] = $result['revisions'][$revisionId];
            $result['siteId'] = $getData['siteId'];
            $result['currentRevisionId'] = $getData['currentRevisionId'];
            $result['stagedRevisionId'] = $getData['stagedRevisionId'];
            unset($result['revisions'], $getData);
        }

        return $result;
    }

    /**
     * Get a list of page id's and page names by a given type
     *
     * @param integer $siteId SiteId
     * @param string  $type   Page Type to Search By
     *
     * @return array
     */
    public function getAllPageIdsAndNamesBySiteThenType($siteId, $type)
    {
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder->select('page.name, page.pageId')
            ->from('\Rcm\Entity\Page', 'page')
            ->join('page.site', 'site')
            ->where('page.pageType = :pageType')
            ->andWhere('site.siteId = :siteId')
            ->setParameter('pageType', $type)
            ->setParameter('siteId', $siteId);

        $result = $queryBuilder->getQuery()->getArrayResult();

        if (empty($result)) {
            return null;
        }

        foreach ($result as $page) {
            $return[$page['pageId']] = $page['name'];
        }

        return $return;
    }
}
