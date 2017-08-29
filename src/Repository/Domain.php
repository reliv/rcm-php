<?php

namespace Rcm\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Join;
use Rcm\Exception\DomainNotFoundException;
use Rcm\Exception\DuplicateDomainException;
use Rcm\Tracking\Model\Tracking;

/**
 * @deprecated Repository should not be used directly, please use the /Rcm/Api/{model}/Repository functions
 * Domain Repository
 *
 * Domain Repository.  Used to get domains for the CMS
 *
 * PHP version 5
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
            ->from(\Rcm\Entity\Domain::class, 'domain', 'domain.domain')
            ->leftJoin('domain.primaryDomain', 'primary')
            ->leftJoin('domain.defaultLanguage', 'language')
            ->leftJoin(
                \Rcm\Entity\Site::class,
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
     * @param string $domainName
     * @param null   $default
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
     * domainExists
     *
     * @param string $domainName
     *
     * @return bool
     */
    public function domainExists($domainName)
    {
        $existingDomain = $this->getDomainByName($domainName);

        return (!empty($existingDomain));
    }

    /**
     * @param        $domainName
     * @param string $createdByUserId
     * @param string $createdReason
     * @param null   $primaryDomain
     * @param bool   $doFlush
     *
     * @return \Rcm\Entity\Domain
     */
    public function createDomain(
        $domainName,
        string $createdByUserId,
        string $createdReason = Tracking::UNKNOWN_REASON,
        $primaryDomain = null,
        $doFlush = false
    ) {
        if (empty($domainName)) {
            throw new DomainNotFoundException('Domain name is required.');
        }

        // Check if exists first
        if ($this->domainExists($domainName)) {
            throw new DuplicateDomainException(
                'Duplicate domains may not be created.'
            );
        }

        $domain = new \Rcm\Entity\Domain(
            $createdByUserId,
            $createdReason
        );
        $domain->setDomainName($domainName);

        if ($primaryDomain instanceof \Rcm\Entity\Domain) {
            $domain->setPrimary($primaryDomain);
        }

        $this->getEntityManager()->persist($domain);

        if ($doFlush) {
            $this->getEntityManager()->flush($domain);
        }

        return $domain;
    }

    /**
     * Search for a domain name by query string.
     *
     * @param string $domainSearchParam Query String... ie. "domain LIKE '[queryString]"
     *
     * @return array
     */
    public function searchForDomain($domainSearchParam)
    {
        $domainsQueryBuilder = $pwsSites = $this->createQueryBuilder('domain');
        $domainsQueryBuilder->where('domain.domain LIKE :domainSearchParam');

        $query = $domainsQueryBuilder->getQuery();
        $query->setParameter('domainSearchParam', $domainSearchParam);

        return $query->getResult();
    }
}
