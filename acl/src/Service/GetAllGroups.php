<?php

namespace Rcm\Acl\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\Entity\Group;

class GetAllGroups
{
    protected $entityManager;

    protected $cachedAllGroups;

    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * Note: This may do some short-term caching in the future
     *
     * @return Group[]
     */
    public function __invoke(): array
    {
        if ($this->cachedAllGroups === null) {
            $this->cachedAllGroups = $this->entityManager->getRepository(Group::class)->findAll();
        }

        return $this->cachedAllGroups;
    }
}
