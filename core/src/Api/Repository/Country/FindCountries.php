<?php

namespace Rcm\Api\Repository\Country;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Country;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindCountries
{
    /**
     * @var \Rcm\Repository\Country
     */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->repository = $entityManager->getRepository(
            Country::class
        );
    }

    /**
     * @param array      $criteria
     * @param array|null $orderBy
     * @param null       $limit
     * @param null       $offset
     * @param array      $options
     *
     * @return array|null
     */
    public function __invoke(
        array $criteria = [],
        array $orderBy = null,
        $limit = null,
        $offset = null,
        array $options = []
    ) {
        return $this->repository->findBy(
            $criteria,
            $orderBy,
            $limit,
            $offset
        );
    }
}
