<?php

namespace Rcm\Api\Repository\Page;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Page;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindOnePage
{
    /**
     * @var \Rcm\Repository\Site
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Page::class
        );
    }

    /**
     * @param array $criteria
     * @param null  $orderBy
     * @param array $options
     *
     * @return null|object
     */
    public function __invoke(
        array $criteria = [],
        $orderBy = null,
        array $options = []
    ) {
        return $this->repository->findOneBy($criteria, $orderBy);
    }
}
