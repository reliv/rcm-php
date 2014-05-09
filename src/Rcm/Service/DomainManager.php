<?php
/**
 * Domain Manager
 *
 * This file contains the class used to manage domain names for the CMS.
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

use Doctrine\ORM\EntityManagerInterface;
use Zend\Cache\Storage\StorageInterface;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Domain Manager.
 *
 * The Domain Manager is used to manage Domains in the CMS.  Each site object is
 * related to a domain for the site.  This allows the CMS to manage multiple sites
 * with one install of the CMS.
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
class DomainManager
{
    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager Doctrine Entity Manager
     * @param StorageInterface       $cache         Zend Cache Manager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        StorageInterface $cache
    ) {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    /**
     * Get the current list of domains and store these in cache for future look ups.
     *
     * @return array
     */
    public function getDomainList()
    {
        //Check Cache for list of domains
        if ($this->cache->hasItem('rcm_domain_list')) {
            return $this->cache->getItem('rcm_domain_list');
        }

        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->select(
            'domain.domain,
            primary.domain primaryDomain,
            language.iso639_2b languageId,
            site.siteId,
            country.iso3 countryId'
        )
            ->from('\Rcm\Entity\Domain', 'domain', 'domain.domain')
            ->leftJoin('domain.primaryDomain', 'primary')
            ->leftJoin('domain.defaultLanguage', 'language')
            ->leftJoin(
                '\Rcm\Entity\Site',
                'site',
                Join::WITH,
                'site.domain = domain.domainId'
            )
            ->leftJoin('site.country', 'country');

        $domainList = $queryBuilder->getQuery()->getArrayResult();

        $this->cache->setItem('rcm_domain_list', $domainList);

        return $domainList;
    }

    /**
     * Get a list of redirects for the CMS.
     *
     * @return array|mixed
     * @todo Move out of the Domain Manager.  Redirects have nothing to do with
     *       domains.
     */
    public function getRedirectList()
    {
        //Check Cache for list of domains
        if ($this->cache->hasItem('rcm_redirect_list')) {
            return $this->cache->getItem('rcm_redirect_list');
        }

        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder
            ->select('r.requestUrl, r.redirectUrl')
            ->from('\Rcm\Entity\Redirect', 'r', 'r.requestUrl');

        $redirectList = $queryBuilder->getQuery()->getArrayResult();

        $this->cache->setItem('rcm_redirect_list', $redirectList);

        return $redirectList;
    }
}