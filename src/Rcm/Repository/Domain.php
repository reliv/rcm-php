<?php

/**
 * Redirect Repository
 *
 * This file contains the redirect repository
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
use Rcm\Entity\Revision;
use Rcm\Entity\Page as PageEntity;
use Rcm\Entity\Site as SiteEntity;
use Rcm\Exception\InvalidArgumentException;


/**
 * Redirect Repository
 *
 * Redirect Repository.  Used to get redirects for the CMS
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
class Redirect extends EntityRepository
{
    /**
     * Get Redirect List From DB
     *
     * @param integer $siteId Site Id
     *
     * @return array
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function getRedirectList($siteId)
    {
        if (empty($siteId) || !is_numeric($siteId)) {
            throw new InvalidArgumentException('Invalid Site Id To Search By');
        }

        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();

        $queryBuilder
            ->select('r.requestUrl, r.redirectUrl')
            ->from('\Rcm\Entity\Redirect', 'r', 'r.requestUrl')
            ->where('r.site = :siteId')
            ->setParameter('siteId', $siteId);

        return $queryBuilder->getQuery()->getArrayResult();
    }
}
