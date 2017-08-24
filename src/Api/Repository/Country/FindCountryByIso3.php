<?php

namespace Rcm\Repository\Country;

use Doctrine\ORM\EntityManager;
use Rcm\Entity\Country;

/**
 * @author James Jervis - https://github.com/jerv13
 */
class FindCountryByIso3
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
     * @param string $iso3
     * @param array  $options
     *
     * @return null|Country
     */
    public function __invoke(
        string $iso3,
        array $options = []
    ) {
        return $this->repository->findOneBy(['iso3' => $iso3]);
    }
}
