<?php

namespace Rcm\ImmutableHistory\Site;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Site;

class SiteIdToDomainName
{
    protected $entityManager;

    protected $cache = [];

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke(string $siteId): string
    {
        if (array_key_exists($siteId, $this->cache)) {
            return $this->cache[$siteId];
        }

        /**
         * Site | null
         */
        $siteEntity = $this->entityManager->find(Site::class, $siteId);

        $domain = 'DOMAIN_NAME_UNKNOWN_BECAUSE_SITE_ID_NOT_FOUND';

        if ($siteEntity) {
            $domain = $siteEntity->getDomain()->getDomainName();
        }

        $this->cache[$siteId] = $domain;

        return $domain;
    }
}
