<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Site;

class SetDomainName
{
    /**
     * @var \Rcm\Repository\Site
     */
    protected $repository;

    protected $em;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Site::class
        );
        $this->em = $entityManager;
    }

    public function __invoke(string $siteId, string $domainName, string $modifiedUserId, string $modifiedReason)
    {
        /** @var Site $site */
        $site = $this->repository->find($siteId);
        $domain = $site->getDomain();
        $domain->setDomainName($domainName);
        $domain->setModifiedByUserId($modifiedUserId, $modifiedReason);
        $this->em->flush($domain);
    }
}
