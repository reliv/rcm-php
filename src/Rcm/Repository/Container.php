<?php

/**
 * Container Repository
 *
 * This file contains the container repository
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
 * Container Repository
 *
 * Container Repository.  Used to get custom container results from the DB
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
class Container extends ContainerAbstract
{
    /**
     * Gets the DB result of the current Published Revision
     *
     * @param integer $siteId Site Id
     * @param string  $name   Name of the container
     *
     * @return mixed
     */
    public function getPublishedRevisionId($siteId, $name)
    {
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select('publishedRevision.revisionId')
            ->from('\Rcm\Entity\Container', 'container')
            ->join('container.publishedRevision', 'publishedRevision')
            ->join('container.site', 'site')
            ->where('site.siteId = :siteId')
            ->andWhere('container.name = :containerName')
            ->setParameter('siteId', $siteId)
            ->setParameter('containerName', $name);

        try {
            return $queryBuilder->getQuery()->getSingleScalarResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Get the Staged Revision Id - Currently not implemented for basic containers
     *
     * @param integer $siteId Site Id
     * @param string  $name   Page Name
     *
     * @return null|integer
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getStagedRevisionId($siteId, $name)
    {
        return null;
    }

    /**
     * Get Revision DB Info
     *
     * @param integer $siteId     Site Id
     * @param string  $name       Page Name
     * @param string  $revisionId Revision Id
     *
     * @return null|array Database Result Set
     */
    public function getRevisionDbInfo($siteId, $name, $revisionId)
    {
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder()
            ->select(
                'container,'
                . 'publishedRevision.revisionId,'
                . 'revision,'
                . 'pluginWrappers,'
                . 'pluginInstances'
            )
            ->from('\Rcm\Entity\Container', 'container')
            ->leftJoin('container.publishedRevision', 'publishedRevision')
            ->leftJoin('container.site', 'site')
            ->leftJoin('container.revisions', 'revision')
            ->leftJoin('revision.pluginWrappers', 'pluginWrappers')
            ->leftJoin('pluginWrappers.instance', 'pluginInstances')
            ->where('site.siteId = :siteId')
            ->andWhere('container.name = :containerName')
            ->andWhere('revision.revisionId = :revisionId')
            ->orderBy('pluginWrappers.renderOrder', 'ASC')
            ->setParameter('siteId', $siteId)
            ->setParameter('containerName', $name)
            ->setParameter('revisionId', $revisionId);

        $getData = $queryBuilder->getQuery()->getSingleResult(
            Query::HYDRATE_ARRAY
        );

        $result = null;

        if (!empty($getData)) {
            $result = $getData[0];
            $result['revision'] = $result['revisions'][$revisionId];
            unset($result['revisions'], $getData);
        }

        return $result;
    }
}
