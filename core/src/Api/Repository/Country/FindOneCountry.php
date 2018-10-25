<?php

namespace Rcm\Api\Repository\Country;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Country;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindOneCountry
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
