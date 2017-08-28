<?php

namespace Rcm\Api\Repository\Redirect;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Redirect;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindAllSiteRedirects
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $options
     *
     * @return Redirect[]
     */
    public function __invoke(
        array $options = []
    ) {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('r')
            ->from(\Rcm\Entity\Redirect::class, 'r')
            ->where('r.siteId IS NOT NULL');

        return $queryBuilder->getQuery()->getResult();
    }
}
