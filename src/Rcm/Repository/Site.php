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
use Rcm\Entity\Site as SiteEntity;

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
                notAuthorizedPage,
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
     * @param bool $mustBeActive
     *
     * @return array array of site objects
     */
    public function getSites($mustBeActive = false)
    {
        $repo = $this->_em
            ->getRepository('\Rcm\Entity\Site');
        if($mustBeActive){
            return $repo ->findBy(array('status' => 'A'));
        }else{
            return $repo->findAll();
        }
    }

    /**
     * Is Valid Site Id
     *
     * @param integer $siteId      Site Id To Check
     * @param boolean $checkActive Should only check active sites.  Default: true
     *
     * @return boolean
     */
    public function isValidSiteId($siteId, $checkActive = false)
    {
        if (empty($siteId) || !is_numeric($siteId)) {
            return false;
        }

        if ($checkActive && in_array($siteId, $this->activeSiteIdCache)) {
            return true;
        }

        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('site.siteId')
            ->from('\Rcm\Entity\Site', 'site')
            ->where('site.siteId = :siteId')
            ->setParameter('siteId', $siteId);

        if ($checkActive) {
            $queryBuilder->andWhere('site.status = :status');
            $queryBuilder->setParameter('status', 'A');
        }

        $result = $queryBuilder->getQuery()->getScalarResult();

        if (!empty($result)) {
            if ($checkActive) {
                $this->activeSiteIdCache[] = $siteId;
            }
            return true;
        }

        return false;
    }

    public function getSiteWidePluginsList($siteId) {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('partial site.{siteId}, plugins')
            ->from('\Rcm\Entity\Site', 'site')
            ->join('site.sitePlugins', 'plugins')
            ->where('site.siteId = :siteId')
            ->setParameter('siteId', $siteId);

        $result = $queryBuilder->getQuery()->getSingleResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if (empty($result) || empty($result['sitePlugins'])) {
            return array();
        }

        return $result['sitePlugins'];
    }

    /**
     * Get Site By Domain Name
     *
     * @param string $domain Domain Name to search by
     *
     * @return SiteEntity
     */
    public function getSiteByDomain($domain) {

        try {
            return $this->getSiteByDomainFromDb($domain);
        } catch (NoResultException $e) {
            $primary = $this->getPrimaryDomain($domain);

            if (!empty($primary)) {
                return $this->getSiteByDomainFromDb($primary->getDomainName());
            }
        }

        return null;
    }

    protected function getSiteByDomainFromDb($domain)
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('site, country, language, domain')
            ->from('\Rcm\Entity\Site', 'site')
            ->join('site.domain', 'domain')
            ->join('site.country', 'country')
            ->join('site.language', 'language')
            ->where('domain.domain = :domainName')
            ->setParameter('domainName', $domain);

        return $queryBuilder->getQuery()->getSingleResult();
    }

    /**
     * Get the Primary Domain for site lookup
     *
     * @param $domain
     *
     * @return null|\Rcm\Entity\Domain
     */
    protected function getPrimaryDomain($domain)
    {
        /** @var \Rcm\Entity\Domain $domain */
        $domain = $this->_em->getRepository('\Rcm\Entity\Domain')
            ->findOneBy(array('domain' => $domain));

        if (empty($domain)) {
            return null;
        }

        $primary = $domain->getPrimary();

        if (!$primary->getPrimary()) {
            return $primary;
        }

        return $this->getPrimaryDomain($primary->getDomainName());
    }

    public function getDoctrine()
    {
        return $this->_em;
    }
}
