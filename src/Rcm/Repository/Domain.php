<?php

/**
 * Domain Repository
 *
 * This file contains the domain repository
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
use Doctrine\ORM\Query\Expr\Join;
use Rcm\Entity\Language as LanguageEntity;
use Rcm\Exception\DomainNotFoundException;
use Rcm\Exception\DuplicateDomainException;

/**
 * Domain Repository
 *
 * Domain Repository.  Used to get domains for the CMS
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
class Domain extends EntityRepository
{
    /**
     * Get the current list of domains.
     *
     * @return array
     */
    public function getActiveDomainList()
    {
        return $this->getDomainLookupQuery()->getArrayResult();
    }

    /**
     * Get the info for a single domain
     *
     * @param string $domain Domain name to search by
     *
     * @return array
     */
    public function getDomainInfo($domain)
    {
        try {
            $result = $this->getDomainLookupQuery($domain)->getSingleResult();
        } catch (NoResultException $e) {
            $result = null;
        }

        return $result;
    }

    /**
     * Get Doctrine Query Object for Domain Lookups
     *
     * @param null $domain
     *
     * @return Query
     */
    private function getDomainLookupQuery($domain = null)
    {
        /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
        $queryBuilder = $this->_em->createQueryBuilder();

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

        if (!empty($domain)) {
            $queryBuilder->andWhere('domain.domain = :domain')
                ->setParameter('domain', $domain);
        }

        return $queryBuilder->getQuery();
    }

    /**
     * getDomainByName
     *
     * @param      $domainName
     * @param null $default
     *
     * @return null|object
     */
    public function getDomainByName($domainName, $default = null)
    {
        if (empty($domainName)) {

            return $default;
        }

        try {
            $result = $this->findOneBy(['domain' => $domainName]);
        } catch (NoResultException $e) {
            $result = $default;
        }

        return $result;
    }

    /**
     * Create Domain
     *
     * @param string $domainName
     * @param mixed $primaryDomain
     *
     * @return \Rcm\Entity\Domain
     */
    public function createDomain(
        $domainName,
        $primaryDomain = null
    ) {
        if (empty($domainName)) {
            throw new DomainNotFoundException('Domain name is required.');
        }

        // Check if exists first
        $existingDomain = $this->getDomainByName($domainName);

        if (!empty($existingDomain)) {
            throw new DuplicateDomainException(
                'Duplicate domains may not be created.'
            );
        }

        $domain = new \Rcm\Entity\Domain();
        $domain->setDomainName($domainName);

        if ($primaryDomain instanceof \Rcm\Entity\Domain) {
            $domain->setPrimary($primaryDomain);
        }

        $this->getEntityManager()->persist($domain);

        return $domain;
    }
}
