<?php

namespace Rcm\Service;

use Doctrine\ORM\EntityManagerInterface;
use Zend\Cache\Storage\StorageInterface;
use Doctrine\ORM\Query\Expr\Join;

class DomainManager
{

    public function __construct(EntityManagerInterface $entityManager, StorageInterface $cache)
    {
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    public function getDomainList()
    {
        //Check Cache for list of domains
        if (!$this->cache->hasItem('rcm_domain_list')) {

            /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
            $queryBuilder = $this->entityManager->createQueryBuilder();

            $queryBuilder->select('domain.domain,
                primary.domain primaryDomain,
                language.iso639_2b languageId,
                site.siteId,
                country.iso3 countryId'
            )->from('\Rcm\Entity\Domain', 'domain', 'domain.domain')
                ->leftJoin('domain.primaryDomain', 'primary')
                ->leftJoin('domain.defaultLanguage', 'language')
                ->leftJoin('\Rcm\Entity\Site', 'site', Join::WITH, 'site.domain = domain.domainId')
                ->leftJoin('site.country', 'country');

            $domainList = $queryBuilder->getQuery()->getArrayResult();

            $this->cache->setItem('rcm_domain_list', $domainList);

            return $domainList;
        }

        return $this->cache->getItem('rcm_domain_list');
    }

    public function getRedirectList()
    {
        //Check Cache for list of domains
        if (!$this->cache->hasItem('rcm_redirect_list')) {

            /** @var \Doctrine\ORM\QueryBuilder $queryBuilder */
            $queryBuilder = $this->entityManager->createQueryBuilder();

            $queryBuilder->select('r.requestUrl, r.redirectUrl')
                ->from('\Rcm\Entity\Redirect', 'r', 'r.requestUrl');

            $redirectList = $queryBuilder->getQuery()->getArrayResult();

            $this->cache->setItem('rcm_redirect_list', $redirectList);

            return $redirectList;
        }

        return $this->cache->getItem('rcm_redirect_list');
    }
}