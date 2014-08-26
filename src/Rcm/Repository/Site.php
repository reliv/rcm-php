<?php

/**
 * Site Repository
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
 * Site Repository
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
class Site extends EntityRepository
{
    protected $activeSiteIdCache = [];

    /**
     * Get Site Info
     *
     * @param integer $siteId Site Id
     *
     * @return mixed|null
     */
    public function getSiteInfo($siteId)
    {
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select(
            'partial site.{
                owner,
                theme,
                status,
                favIcon,
                loginPage,
                siteLayout,
                siteTitle,
                siteId
            },
            language,
            country'
        )->from('\Rcm\Entity\Site', 'site')
            ->join('site.country', 'country')
            ->join('site.language', 'language')
            ->where('site.siteId = :siteId')
            ->setParameter('siteId', $siteId);

        try {
            return $queryBuilder->getQuery()->getSingleResult(
                Query::HYDRATE_ARRAY
            );
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Get All Active Site Objects
     *
     * @return array Array of Site Objects
     */
    public function getAllActiveSites()
    {
        return $this->_em
            ->getRepository('\Rcm\Entity\Site')
            ->findBy(array('status' => 'A'));
    }

    /**
     * Is Valid Site Id
     *
     * @param integer $siteId      Site Id To Check
     * @param boolean $checkActive Should only check active sites.  Default: true
     *
     * @return boolean
     */
    public function isValidSiteId($siteId, $checkActive = true)
    {
        if (empty($siteId) || !is_numeric($siteId)) {
            return false;
        }

        $findBy = array(
            'siteId' => $siteId,
        );

        if ($checkActive) {
            $findBy['status'] = 'A';
        }

        if ($checkActive && in_array($siteId, $this->activeSiteIdCache)) {
            return true;
        }

        $result = $this->findOneBy($findBy);

        if (!empty($result)) {
            if ($checkActive) {
                $this->activeSiteIdCache[] = $siteId;
            }
            return true;
        }

        return false;
    }
}
