<?php


namespace Rcm\Api\Repository\Site;


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

    public function __invoke(string $siteId, string $domain)
    {
        /**
         * @var Site $site
         */
        $site = $this->repository->find($siteId);
        $domain = $site->getDomain();
        $domain->setDomainName($domain);
        $this->em->flush($domain);
    }
}
