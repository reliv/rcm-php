<?php

namespace Rcm\Api\Repository\Site;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Site;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class SetTheme
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

    /**
     * @param string $siteId
     * @param string $themeName
     * @param string $modifiedUserId
     * @param string $modifiedReason
     *
     * @return void
     */
    public function __invoke(
        string $siteId,
        string $themeName,
        string $modifiedUserId,
        string $modifiedReason
    ) {
        /** @var Site $site */
        $site = $this->repository->find($siteId);
        $site->setTheme($themeName);
        $site->setModifiedByUserId($modifiedUserId, $modifiedReason);
        $this->em->flush($site);
    }
}
